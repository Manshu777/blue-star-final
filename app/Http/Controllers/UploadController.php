<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            Log::error('Inactive plan attempted upload', ['user_id' => $user->id, 'plan' => $plan->name]);
            return $this->errorResponse('Your plan is inactive.', 403);
        }

        // Check daily upload limit
        if ($plan->photo_upload_limit > 0) {
            $todayUploads = Photo::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->count();
            $newFilesCount = count($request->file('files') ?? []);
            if ($todayUploads + $newFilesCount > $plan->photo_upload_limit) {
                Log::error('Daily upload limit exceeded', ['user_id' => $user->id, 'limit' => $plan->photo_upload_limit]);
                return $this->errorResponse('Daily upload limit exceeded.', 403);
            }
        }

        // Check storage limit
        if ($plan->storage_limit > 0) {
            $usedStorage = Photo::where('user_id', $user->id)->sum('file_size');
            $newFilesSize = 0;
            foreach ($request->file('files') ?? [] as $file) {
                $newFilesSize += $file->getSize() / (1024 * 1024); // Convert to MB
            }
            if (($usedStorage + $newFilesSize) / 1024 > $plan->storage_limit) {
                Log::error('Storage limit exceeded', ['user_id' => $user->id, 'used' => $usedStorage, 'limit' => $plan->storage_limit]);
                return $this->errorResponse('Storage limit exceeded.', 403);
            }
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'license_type' => 'nullable|string|in:commercial,personal',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string|max:500',
            'tour_provider' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'folder_name' => 'nullable|string|max:255', // Maps to event
            'files.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:5120',
        ]);

        $photos = [];
        $files = $request->file('files') ?? [];

        if (empty($files)) {
            return $this->errorResponse('No files uploaded.', 422);
        }

        foreach ($files as $file) {
            if (!$file->isValid()) {
                Log::error('Invalid file uploaded', ['error' => $file->getErrorMessage(), 'user_id' => $user->id]);
                continue;
            }

            // Generate file paths
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $fileExtension;
            $directory = 'uploads';
            $path = "uploads/$fileName";

            // Upload to S3
            $uploadedPath = Storage::disk('s3')->putFileAs($directory, $file, $fileName);
            if (!$uploadedPath) {
                Log::error('S3 upload failed', ['user_id' => $user->id, 'file' => $fileName]);
                continue;
            }

            // Verify S3 object availability
            $maxRetries = 5;
            $retryDelay = 1; // seconds
            $attempt = 0;
            while ($attempt < $maxRetries) {
                try {
                    Storage::disk('s3')->get($uploadedPath);
                    break;
                } catch (\Exception $e) {
                    if ($attempt === $maxRetries - 1) {
                        Log::error('S3 object not available', ['user_id' => $user->id, 'path' => $uploadedPath]);
                        continue 2; // Skip to next file
                    }
                    sleep($retryDelay);
                    $attempt++;
                }
            }

            // Initialize metadata and tags
            $metadata = [];
            $tags = $validated['tags'] ? trim($validated['tags'], ',') : '';

            // Extract EXIF data
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png']) && function_exists('exif_read_data')) {
                $exif = @exif_read_data($file->getRealPath());
                if ($exif) {
                    $metadata['date'] = isset($exif['DateTimeOriginal']) ? Carbon::parse($exif['DateTimeOriginal'])->toDateTimeString() : now();
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

            // Facial Recognition
            if ($plan->facial_recognition_enabled && in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
                if ($plan->name === 'Free') {
                    $todaySearches = Photo::where('user_id', $user->id)
                        ->whereNotNull('metadata->face_match_similarity')
                        ->whereDate('created_at', Carbon::today())
                        ->count();
                    if ($todaySearches >= 5) {
                        Log::error('Facial recognition limit exceeded', ['user_id' => $user->id]);
                        continue;
                    }
                }

                try {
                    // Verify uploaded file exists in S3
                    if (!Storage::disk('s3')->exists($uploadedPath)) {
                        Log::error('Uploaded file not found in S3', ['user_id' => $user->id, 'path' => $uploadedPath]);
                        $tags = $tags ? "$tags,s3_object_missing" : 's3_object_missing';
                        continue;
                    }

                    // Get reference selfie path
                    $selfiePath = $user->reference_selfie_path;
                    if ($selfiePath) {
                        // Verify selfie exists in S3
                        $attempt = 0;
                        while ($attempt < $maxRetries) {
                            try {
                                Storage::disk('s3')->get($selfiePath);
                                break;
                            } catch (\Exception $e) {
                                if ($attempt === $maxRetries - 1) {
                                    Log::error('Reference selfie not found in S3', ['user_id' => $user->id, 'selfie_path' => $selfiePath]);
                                    $tags = $tags ? "$tags,no_reference_selfie" : 'no_reference_selfie';
                                    continue 2; // Skip to next file
                                }
                                sleep($retryDelay);
                                $attempt++;
                            }
                        }
                    } else {
                        Log::warning('No reference selfie available', ['user_id' => $user->id]);
                        $tags = $tags ? "$tags,no_reference_selfie" : 'no_reference_selfie';
                        continue;
                    }

                    // Log Rekognition attempt
                    Log::info('Rekognition CompareFaces attempt', [
                        'user_id' => $user->id,
                        'bucket' => env('AWS_BUCKET'),
                        'source_path' => $selfiePath,
                        'target_path' => $uploadedPath
                    ]);

                    // Detect faces
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
                        Log::info('No faces detected', ['user_id' => $user->id, 'photo_path' => $uploadedPath]);
                        $tags = $tags ? "$tags,no_face" : 'no_face';
                    } else {
                        Log::info('Faces detected', ['user_id' => $user->id, 'photo_path' => $uploadedPath, 'count' => count($faceDetails)]);
                        $tags = $tags ? "$tags,face_detected" : 'face_detected';

                        // Compare faces
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
                            Log::info('Face match found', ['user_id' => $user->id, 'similarity' => $similarity]);
                            $tags = $tags ? "$tags,face_matched" : 'face_matched';
                            $metadata['face_match_similarity'] = $similarity;
                        } else {
                            Log::info('No face match', ['user_id' => $user->id, 'photo_path' => $uploadedPath]);
                            $tags = $tags ? "$tags,face_detected_no_match" : 'face_detected_no_match';
                        }
                    }
                } catch (\Aws\Exception\AwsException $e) {
                    Log::error('Rekognition error', [
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                        'code' => $e->getAwsErrorCode(),
                    ]);
                    $tags = $tags ? "$tags,rekognition_error" : 'rekognition_error';
                }
            }

            // Create photo record
            $photo = Photo::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_path' => $uploadedPath,
                'price' => 0,
                'is_featured' => $validated['is_featured'] ?? false,
                'license_type' => 'personal',
                'tags' => $tags,
                'metadata' => json_encode($metadata),
                'tour_provider' => $validated['tour_provider'] ?? null,
                'location' => $metadata['location'] ?? $validated['location'] ?? null,
                'event' => $validated['folder_name'] ?? null,
                'date' => $metadata['date'] ?? now(),
                'file_size' => $file->getSize() / (1024 * 1024), // Store in MB
            ]);

            Log::info('Photo uploaded successfully', [
                'user_id' => $user->id,
                'photo_id' => $photo->id,
                'path' => $uploadedPath,
            ]);

            $photos[] = [
                'photo' => $photo,
                'url' => Storage::disk('s3')->url($uploadedPath),
            ];
        }

        if (empty($photos)) {
            return $this->errorResponse('No valid files uploaded.', 422);
        }

        // Return response based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'photos' => $photos,
            ], 201);
        }

        return redirect()->route('photos.store')->with([
            'success' => 'Photos uploaded successfully.',
            'urls' => array_column($photos, 'url'),
        ]);
    } catch (ValidationException $e) {
        Log::error('Validation failed', ['errors' => $e->errors(), 'user_id' => Auth::id()]);
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Upload error', [
            'user_id' => Auth::id(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
        return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage())->withInput();
    }
}

    protected function errorResponse($message, $status)
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }
        return redirect()->back()->with('error', $message)->withInput();
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


         public function analyzeImage(Request $request)
{
    try {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png|max:15360',
        ]);

        // Check if file was uploaded successfully
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            throw new \Exception('Invalid image file upload');
        }

        $imageFile = $request->file('image');
        
        // Verify file size before processing
        if ($imageFile->getSize() > 15360 * 1024) { // Convert KB to bytes
            throw new \Exception('Image file size exceeds maximum limit');
        }

        // Read image file
        $imageBytes = @file_get_contents($imageFile->getRealPath());
        if ($imageBytes === false) {
            throw new \Exception('Failed to read image file');
        }

        // Initialize AWS Rekognition client
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Verify AWS credentials
        if (empty(env('AWS_ACCESS_KEY_ID')) || empty(env('AWS_SECRET_ACCESS_KEY'))) {
            throw new \Exception('AWS credentials not configured');
        }

        // Perform image analysis
        $result = $rekognition->detectLabels([
            'Image' => ['Bytes' => $imageBytes],
            'MaxLabels' => 10,
            'MinConfidence' => 80,
        ]);

        // Process results
        $tags = collect($result['Labels'])->pluck('Name')->toArray();
        
        if (empty($tags)) {
            return response()->json([
                'success' => true,
                'tags' => '',
                'message' => 'No labels detected with sufficient confidence'
            ]);
        }

        return response()->json([
            'success' => true,
            'tags' => implode(', ', $tags),
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Invalid input: ' . $e->getMessage(),
            'errors' => $e->errors()
        ], 422);
    } catch (\Aws\Exception\CredentialsException $e) {
        Log::error('AWS credentials error: ' . $e->getAwsErrorMessage());
        return response()->json([
            'success' => false,
            'message' => 'AWS credentials invalid'
        ], 500);
    } catch (\Aws\Exception\AwsException $e) {
        Log::error('Rekognition error: ' . $e->getAwsErrorMessage());
        return response()->json([
            'success' => false,
            'message' => 'AWS analysis failed: ' . $e->getAwsErrorMessage()
        ], 500);
    } catch (\Exception $e) {
        Log::error('General analysis error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Analysis failed: ' . $e->getMessage()
        ], 500);
    }
}




   
    public function destroy(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $photo = Photo::where('user_id', $user->id)->findOrFail($id);

            // Delete from AWS S3
            if ($photo->public_id) {
                try {
                    Storage::disk('s3')->delete($photo->public_id);
                } catch (\Exception $e) {
                    Log::error('S3 deletion error', [
                        'user_id' => $user->id,
                        'photo_id' => $photo->id,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            // Delete from database
            $photo->delete();

            Log::info('Photo deleted successfully', [
                'user_id' => $user->id,
                'photo_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Photo deleted successfully.'], 200);
            }

            return redirect()->route('photos.store')->with('success', 'Photo deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete error', [
                'user_id' => Auth::id(),
                'photo_id' => $id,
                'message' => $e->getMessage(),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
