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

            // Custom path for storing the file on S3
            $path = "blue-star-media/" . date('Y') . "/{$validated['price']}/{$validated['license_type']}/" . time() . '_' . $file->getClientOriginalName();

            // Upload file to S3 using Laravel's Storage facade
            Storage::disk('s3')->put($path, fopen($file->getRealPath(), 'r'), 'public');

            // Store metadata (Exif for image files)
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

            // Set automatic tags
            $autoTags = 'face_detected,location_based';
            $tags = $validated['tags'] ? $validated['tags'] . ',' . $autoTags : $autoTags;

            // Create the photo record in the database
            $photo = Photo::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_path' => $path,
                'price' => $validated['price'],
                'is_featured' => $validated['is_featured'] ?? false,
                'license_type' => $validated['license_type'],
                'tags' => $tags,
                'metadata' => json_encode($metadata),
            ]);

            // Get the URL of the uploaded file on S3
            $url = Storage::disk('s3')->url($path);

            // Facial recognition with Rekognition (if it's an image)
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
                $result = $this->rekognition->detectFaces([
                    'Image' => [
                        'S3Object' => [
                            'Bucket' => env('AWS_BUCKET'),
                            'Name' => $path,
                        ],
                    ],
                ]);

                // You can process the result here if needed
                // Example: Check if faces are detected
                $facesDetected = count($result['FaceDetails']) > 0;
                if ($facesDetected) {
                    // Do something, like adding tags for face detection
                    $tags .= ',faces_detected';
                }
            }

            return response()->json(['success' => true, 'photo' => $photo, 'url' => $url], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during upload: ' . $e->getMessage()], 500);
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
