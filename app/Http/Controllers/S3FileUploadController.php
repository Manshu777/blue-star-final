<?php
namespace App\Http\Controllers;

use App\Models\Photo;
use Aws\Exception\AwsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
class S3FileUploadController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        $plan = $user->plan;

        $photos = Photo::where('user_id', $user->id)
            ->latest()
            ->get()
            ->groupBy('event')
            ->toArray();

        // Format photos
        $photos = array_map(function ($eventPhotos) {
            return array_map(function ($photo) {
                return [
                    'url'      => Storage::disk('s3')->url($photo['image_path']),
                    'title'    => $photo['title'],
                    'id'       => $photo['id'],
                    'location' => $photo['location'] ?? 'No Location',
                   'tags'     => $photo['tags'] ? array_map('trim', explode(',', $photo['tags'])) : [],
                    'date'     => $photo['date'] ? \Carbon\Carbon::parse($photo['date'])->diffForHumans() : '',
                ];
            }, $eventPhotos);
        }, $photos);

        // Get 10 most recent uploads
        $recentUploads = Photo::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($photo) {
                return [
                    'url'      => Storage::disk('s3')->url($photo->image_path),
                    'title'    => $photo->title,
                    'id'       => $photo->id,
                    'location' => $photo->location ?? 'No Location',
                  'tags'     => $photo['tags'] ? array_map('trim', explode(',', $photo['tags'])) : [],
                    'date'     => $photo->date ? \Carbon\Carbon::parse($photo->date)->diffForHumans() : '',
                ];
            });

        // Calculate totals and usage
        $totalUploads = Photo::where('user_id', $user->id)->count();
        $storageUsedMB = Photo::where('user_id', $user->id)->sum('file_size');
        $storageUsedGB  = round($storageUsedMB / 1024, 2);
        $storageLimitGB = $plan->storage_limit ?? 0; // Assuming limit in GB, 0 for unlimited
        $storageUsagePercent = $storageLimitGB > 0 ? round(($storageUsedGB / $storageLimitGB) * 100, 2) : 0;

        $todayUploads = Photo::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $dailyUploadLimit = $plan->photo_upload_limit ?? 0; // 0 for unlimited
        $dailyUploadUsagePercent = $dailyUploadLimit > 0 ? round(($todayUploads / $dailyUploadLimit) * 100, 2) : 0;

                                          // Additional stats
        $totalEvents = count($photos); // Number of unique events/albums
        $featuredPhotos = Photo::where('user_id', $user->id)
            ->where('is_featured', true)
            ->count();
        $photosWithFaces = Photo::where('user_id', $user->id)
            ->whereNotNull('metadata->face_match_similarity')
            ->count();

        return view('upload-form', compact(
            'photos',
            'recentUploads',
            'totalUploads',
            'storageUsedGB',
            'storageLimitGB',
            'storageUsagePercent',
            'todayUploads',
            'dailyUploadLimit',
            'dailyUploadUsagePercent',
            'totalEvents',
            'featuredPhotos',
            'photosWithFaces',
            'plan'
        ));

    }

    public function index(Request $request)
    {

    }

    public function upload(Request $request)
    {
        try {
            // Validate the incoming file
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
            ]);

            // Get the file from the request
            $file = $request->file('file');

            // Ensure the file is valid
            if (! $file->isValid()) {
                \Log::error('Invalid file uploaded: ' . $file->getErrorMessage());
                return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Invalid file uploaded.'], 422)
                : redirect()->route('upload.form')->with('error', 'Invalid file uploaded.');
            }

            // Generate a unique filename
            $fileName  = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $directory = 'uploads';

            // Log file details
            \Log::info('Attempting S3 upload', [
                'original_name' => $file->getClientOriginalName(),
                'size'          => $file->getSize(),
                'mime'          => $file->getMimeType(),
                'target_path'   => $directory . '/' . $fileName,
                'bucket'        => config('filesystems.disks.s3.bucket'),
                'region'        => config('filesystems.disks.s3.region'),
            ]);

            // Store the file in S3 without specifying ACL
            $path = Storage::disk('s3')->putFileAs(
                $directory,
                $file,
                $fileName
            );

            // Verify the path is not empty
            if (empty($path)) {
                \Log::error('S3 upload failed: Empty path returned');
                return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Failed to generate S3 file path.'], 500)
                : redirect()->route('upload.form')->with('error', 'Failed to generate S3 file path.');
            }

            // Get the full URL of the uploaded file
            $url = Storage::disk('s3')->url($path);

            \Log::info('S3 upload successful', ['path' => $path, 'url' => $url]);

            return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'File uploaded successfully', 'url' => $url, 'path' => $path])
            : redirect()->route('upload.form')->with(['success' => 'File uploaded successfully', 'url' => $url]);

        } catch (AwsException $e) {
            \Log::error('S3 AWS Exception: ' . $e->getMessage(), [
                'aws_error'   => $e->getAwsErrorCode(),
                'aws_message' => $e->getAwsErrorMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
            return $request->expectsJson()
            ? response()->json(['success' => false, 'message' => 'S3 upload failed: ' . $e->getMessage()], 500)
            : redirect()->route('upload.form')->with('error', 'S3 upload failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $request->expectsJson()
            ? response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()], 500)
            : redirect()->route('upload.form')->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }
}
