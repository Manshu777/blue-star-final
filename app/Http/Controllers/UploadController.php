<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Aws\Sdk;


use Illuminate\Support\Str;
class UploadController extends Controller
{

    protected $s3;


    protected $rekognition;

    // Inject RekognitionClient via constructor
    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $this->rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }


    /**
     * Display a listing of the photos (API).
     */
    public function index()
    {
        return response()->json(Photo::all());
    }

    /**
     * Store a newly created photo (API).
     */


   public function store(Request $request)
{
    try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'license_type' => 'required|string|in:commercial,personal',
                'is_featured' => 'nullable|boolean',
                'tags' => 'nullable|string|max:500',
                'file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            ]);

            // Retrieve the file from the request
            $file = $request->file('file');

            // Ensure the file is valid
            if (!$file->isValid()) {
                \Log::error('Invalid file uploaded: ' . $file->getErrorMessage());
                return response()->json(['success' => false, 'message' => 'Invalid file uploaded.'], 422);
            }

            // Generate a unique filename
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $fileExtension;

            // Use the same directory as the upload method
            $directory = 'uploads'; // Or 'bluestatlaravel/uploads' if you want the bucket name in the path
            $path = "$directory/$fileName";

            // Log file details
            \Log::info('Attempting S3 upload', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'target_path' => $path,
                'bucket' => config('filesystems.disks.s3.bucket'),
                'region' => config('filesystems.disks.s3.region'),
            ]);

            // Verify S3 disk configuration
            $s3Config = config('filesystems.disks.s3');
            \Log::info('S3 Configuration', [
                'bucket' => $s3Config['bucket'],
                'region' => $s3Config['region'],
                'key' => $s3Config['key'] ? '****' : 'missing',
                'secret' => $s3Config['secret'] ? '****' : 'missing',
            ]);

            // Upload file to S3 (without specifying ACL, matching the upload method)
            try {
                $uploadedPath = Storage::disk('s3')->putFileAs(
                    $directory,
                    $file,
                    $fileName
                );
            } catch (\Exception $e) {
                \Log::error('S3 putFileAs failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw new \Exception('S3 upload failed: ' . $e->getMessage());
            }

            // Verify the path is not empty
            if (empty($uploadedPath)) {
                \Log::error('S3 upload failed: Empty path returned');
                return response()->json(['success' => false, 'message' => 'Failed to generate S3 file path.'], 500);
            }

            // Verify file exists in S3
            $exists = Storage::disk('s3')->exists($uploadedPath);
            \Log::info('S3 file existence check', ['path' => $uploadedPath, 'exists' => $exists]);
            if (!$exists) {
                \Log::error('S3 upload failed: File does not exist in bucket');
                return response()->json(['success' => false, 'message' => 'File was not uploaded to S3.'], 500);
            }

            // Get the URL of the uploaded file
            $url = Storage::disk('s3')->url($uploadedPath);

            // Store metadata (Exif for image files)
            $metadata = [];
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png']) && function_exists('exif_read_data')) {
                $exif = @exif_read_data($file->getRealPath());
                if ($exif) {
                    $metadata['date'] = $exif['DateTimeOriginal'] ?? null;
                    $metadata['location'] = $exif['GPSLatitude'] ?? null;
                }
            }

            // Set automatic tags
            $autoTags = 'face_detected,location_based';
            $tags = $validated['tags'] ? $validated['tags'] . ',' . $autoTags : $autoTags;


            // Create the photo record in the database
            $photo = Photo::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_path' => $uploadedPath,
                'price' => $validated['price'],
                'is_featured' => $validated['is_featured'] ?? false,
                'license_type' => $validated['license_type'],
                'tags' => $tags,
                'metadata' => json_encode($metadata),
            ]);

            \Log::info('S3 upload successful', ['path' => $uploadedPath, 'url' => $url]);

            return response()->json(['success' => true, 'photo' => $photo, 'url' => $url], 201);

        } catch (ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (S3Exception $e) {
            \Log::error('S3 AWS Exception: ' . $e->getMessage(), [
                'aws_error' => $e->getAwsErrorCode(),
                'aws_message' => $e->getAwsErrorMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'S3 upload failed: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()], 500);
        }
    
}


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
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'license_type' => 'required|string|in:personal,commercial',
                'is_featured' => 'boolean',
                'tags' => 'nullable|string|max:500',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            ]);

            if ($request->hasFile('file')) {
                Storage::disk('s3')->delete($photo->image_path);

                $file = $request->file('file');
                $path = $file->storeAs('media', time() . '_' . $file->getClientOriginalName(), 's3');
                $validated['image_path'] = $path;

                $metadata = [];
                if (in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
                    if (function_exists('\exif_read_data')) {
                        $exif = @\exif_read_data($file->getRealPath());
                        if ($exif) {
                            $metadata['date'] = $exif['DateTimeOriginal'] ?? null;
                            $metadata['location'] = $exif['GPSLatitude'] ?? null;
                        }
                    }
                }
                $validated['metadata'] = json_encode($metadata);
            }

            $autoTags = 'face_detected,location_based';
            $tags = $validated['tags'] ? $validated['tags'] . ',' . $autoTags : $autoTags;
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
