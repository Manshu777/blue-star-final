<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;

class UploadController extends Controller
{

    protected $s3;
    protected $rekognition;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $this->rekognition = new RekognitionClient([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION', 'eu-west-1'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function store(Request $request)
{
    try {
        // Get authenticated user and their plan
        $user = Auth::user();
        $plan = $user->plan;

        // Validate plan restrictions
        if (!$plan->is_active) {
            \Log::error('Inactive plan attempted upload', ['user_id' => $user->id, 'plan' => $plan->name]);
            return response()->json(['success' => false, 'message' => 'Your plan is inactive.'], 403);
        }

        // Check daily upload limit (e.g., 5 for Free plan)
        if ($plan->photo_upload_limit > 0) {
            $todayUploads = Photo::where('user_id', $user->id)
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();
            if ($todayUploads >= $plan->photo_upload_limit) {
                \Log::error('Daily upload limit exceeded', ['user_id' => $user->id, 'limit' => $plan->photo_upload_limit]);
                return response()->json(['success' => false, 'message' => 'Daily upload limit exceeded.'], 403);
            }
        }

        // Check storage limit (e.g., 1GB for Free, 10GB for Basic)
        if ($plan->storage_limit > 0) {
            $usedStorage = Photo::where('user_id', $user->id)->sum('file_size');
            $newFileSize = $request->file('file')->getSize() / (1024 * 1024); // Convert to MB
            if (($usedStorage + $newFileSize) / 1024 > $plan->storage_limit) {
                \Log::error('Storage limit exceeded', ['user_id' => $user->id, 'used' => $usedStorage, 'limit' => $plan->storage_limit]);
                return response()->json(['success' => false, 'message' => 'Storage limit exceeded.'], 403);
            }
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'license_type' => 'required|string|in:commercial,personal',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string|max:500',
            'tour_provider' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'event' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png|max:5120', // Rekognition requires <5MB
        ]);

        // Validate file
        $file = $request->file('file');
        if (!$file->isValid()) {
            \Log::error('Invalid file uploaded', ['error' => $file->getErrorMessage(), 'user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Invalid file uploaded.'], 422);
        }

        // Generate file paths
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $fileExtension;
        $directory = 'uploads';
        $path = "uploads/$fileName";

        // Apply watermark for Free plan
        $watermarkedPath = $path;
        if ($plan->name === 'Free' && in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            try {
                $image = Image::make($file);
                $image->text('Blue Star Memory - Preview', 10, 10, function ($font) {
                    $font->size(20);
                    $font->color('#ffffff');
                    $font->align('left');
                    $font->valign('top');
                });
                $watermarkedPath = "uploads/watermarked_$fileName";
                Storage::disk('s3')->put($watermarkedPath, $image->encode());
                \Log::info('Watermark applied', ['user_id' => $user->id, 'path' => $watermarkedPath]);
            } catch (\Exception $e) {
                \Log::error('Watermarking failed', ['user_id' => $user->id, 'message' => $e->getMessage()]);
                // Continue without watermark to avoid blocking upload
            }
        }

        // Upload to S3
        $uploadedPath = Storage::disk('s3')->putFileAs($directory, $file, $fileName);
        if (!$uploadedPath) {
            \Log::error('S3 upload failed', ['user_id' => $user->id, 'file' => $fileName]);
            return response()->json(['success' => false, 'message' => 'S3 upload failed.'], 500);
        }

        // Initialize metadata and tags
        $metadata = [];
        $tags = $validated['tags'] ? trim($validated['tags'], ',') : '';

        // Extract EXIF data for grouping
        if (in_array($file->getMimeType(), ['image/jpeg', 'image/png']) && function_exists('exif_read_data')) {
            $exif = @exif_read_data($file->getRealPath());
            if ($exif) {
                $metadata['date'] = isset($exif['DateTimeOriginal']) ? \Carbon\Carbon::parse($exif['DateTimeOriginal'])->toDateTimeString() : now();
                if (isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                    $metadata['location'] = "{$exif['GPSLatitude']},{$exif['GPSLongitude']}";
                } else {
                    $metadata['location'] = $validated['location'] ?? null;
                }
            } else {
                $metadata['date'] = now();
                $metadata['location'] = $validated['location'] ?? null;
            }
        } else {
            $metadata['date'] = now();
            $metadata['location'] = $validated['location'] ?? null;
        }

        // Facial Recognition (only for plans with feature enabled)
        if ($plan->facial_recognition_enabled && in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            // Free plan: Limit to 5 facial recognition searches per day
            if ($plan->name === 'Free') {
                $todaySearches = Photo::where('user_id', $user->id)
                    ->whereNotNull('metadata->face_match_similarity')
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->count();
                if ($todaySearches >= 5) {
                    \Log::error('Facial recognition limit exceeded', ['user_id' => $user->id]);
                    return response()->json(['success' => false, 'message' => 'Facial recognition limit exceeded for Free plan.'], 403);
                }
            }

            try {
                // Detect faces in the uploaded photo
                $detectResult = $this->rekognition->detectFaces([
                    'Image' => [
                        'S3Object' => [
                            'Bucket' => env('AWS_BUCKET'),
                            'Name' => $uploadedPath,
                        ],
                    ],
                    'Attributes' => ['ALL'],
                ]);

                $faceDetails = $detectResult->get('FaceDetails');
                if (empty($faceDetails)) {
                    \Log::info('No faces detected', ['user_id' => $user->id, 'photo_path' => $uploadedPath]);
                    $tags = $tags ? "$tags,no_face" : 'no_face';
                } else {
                    \Log::info('Faces detected', ['user_id' => $user->id, 'photo_path' => $uploadedPath, 'count' => count($faceDetails)]);
                    $tags = $tags ? "$tags,face_detected" : 'face_detected';

                    // Compare with user's reference selfie
                    $selfiePath = $user->reference_selfie_path;
                    if ($selfiePath) {
                        $compareResult = $this->rekognition->compareFaces([
                            'SourceImage' => [
                                'S3Object' => [
                                    'Bucket' => env('AWS_BUCKET'),
                                    'Name' => $selfiePath,
                                ],
                            ],
                            'TargetImage' => [
                                'S3Object' => [
                                    'Bucket' => env('AWS_BUCKET'),
                                    'Name' => $uploadedPath,
                                ],
                            ],
                            'SimilarityThreshold' => 70,
                        ]);

                        $faceMatches = $compareResult->get('FaceMatches');
                        if (!empty($faceMatches)) {
                            $similarity = $faceMatches[0]['Similarity'];
                            \Log::info('Face match found', ['user_id' => $user->id, 'similarity' => $similarity]);
                            $tags = $tags ? "$tags,face_matched" : 'face_matched';
                            $metadata['face_match_similarity'] = $similarity;
                        } else {
                            \Log::info('No face match', ['user_id' => $user->id, 'photo_path' => $uploadedPath]);
                            $tags = $tags ? "$tags,face_detected_no_match" : 'face_detected_no_match';
                        }
                    } else {
                        \Log::warning('No reference selfie available', ['user_id' => $user->id]);
                        $tags = $tags ? "$tags,face_detected_no_selfie" : 'face_detected_no_selfie';
                    }
                }
            } catch (\Aws\Exception\AwsException $e) {
                \Log::error('Rekognition error', [
                    'user_id' => $user->id,
                    'message' => $e->getMessage(),
                    'code' => $e->getAwsErrorCode()
                ]);
                $tags = $tags ? "$tags,rekognition_error" : 'rekognition_error';
            }
        } else {
            \Log::info('Facial recognition skipped', [
                'user_id' => $user->id,
                'reason' => $plan->facial_recognition_enabled ? 'unsupported file type' : 'disabled for plan'
            ]);
        }

        // Create photo record
        $photo = Photo::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $uploadedPath,
            'watermarked_path' => $plan->name === 'Free' ? $watermarkedPath : null,
            'price' => $validated['price'],
            'is_featured' => $validated['is_featured'] ?? false,
            'license_type' => $validated['license_type'],
            'tags' => $tags,
            'metadata' => json_encode($metadata),
            'tour_provider' => $validated['tour_provider'] ?? null,
            'location' => $metadata['location'] ?? null,
            'event' => $validated['event'] ?? null,
            'date' => $metadata['date'] ?? now(),
            'file_size' => $file->getSize() / (1024 * 1024), // Store in MB
        ]);

        \Log::info('Photo uploaded successfully', [
            'user_id' => $user->id,
            'photo_id' => $photo->id,
            'path' => $uploadedPath
        ]);

        return response()->json([
            'success' => true,
            'photo' => $photo,
            'url' => Storage::disk('s3')->url($uploadedPath)
        ], 201);
    } catch (ValidationException $e) {
        \Log::error('Validation failed', ['errors' => $e->errors(), 'user_id' => Auth::id()]);
        return response()->json(['success' => false, 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Upload error', [
            'user_id' => Auth::id(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
    }
}
    public function index()
    {
        return response()->json(Photo::all());
    }

    /**
     * Store a newly created photo (API).
     */

    /**
     * Display the specified photo (API).
     */
    public function show(Photo $photo)
    {
        return response()->json($photo);
    }

    /**
     * Update the specified photo (API).
     */
    public function update(Request $request, Photo $photo)
    {
        try {
            $validated = $request->validate([
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string|max:1000',
                'price'        => 'required|numeric|min:0',
                'license_type' => 'required|string|in:personal,commercial',
                'is_featured'  => 'boolean',
                'tags'         => 'nullable|string|max:500',
                'file'         => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            ]);

            if ($request->hasFile('file')) {
                Storage::disk('s3')->delete($photo->image_path);

                $file                    = $request->file('file');
                $path                    = $file->storeAs('media', time() . '_' . $file->getClientOriginalName(), 's3');
                $validated['image_path'] = $path;

                $metadata = [];
                if (in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
                    if (function_exists('\exif_read_data')) {
                        $exif = @\exif_read_data($file->getRealPath());
                        if ($exif) {
                            $metadata['date']     = $exif['DateTimeOriginal'] ?? null;
                            $metadata['location'] = $exif['GPSLatitude'] ?? null;
                        }
                    }
                }
                $validated['metadata'] = json_encode($metadata);
            }

            $autoTags          = 'face_detected,location_based';
            $tags              = $validated['tags'] ? $validated['tags'] . ',' . $autoTags : $autoTags;
            $validated['tags'] = $tags;

            $photo->update($validated);

            return response()->json(['success' => true, 'photo' => $photo]);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during update: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified photo (API).
     */
    public function destroy(Photo $photo)
    {
        try {
            Storage::disk('s3')->delete($photo->image_path);
            $photo->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during deletion: ' . $e->getMessage()], 500);
        }
    }
}
