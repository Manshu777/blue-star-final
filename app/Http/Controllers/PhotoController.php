<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PhotoController extends Controller
{
    /**
     * Display a listing of the photos (API).
     */
    public function index()
    {
        return response()->json(Photo::all());
    }
    public function grouped()
    {
        $groups = Photo::select('tour_provider', 'location', 'date', 'event')
            ->groupBy(['tour_provider', 'location', 'date', 'event'])
            ->get();
        return view('photos.grouped', compact('groups'));
    }

    public function share(Photo $photo)
    {
        $shareLink = Storage::disk('s3')->temporaryUrl(
            $photo->watermarked_path ?? $photo->image_path,
            now()->addMinutes(60)
        );
        return view('photos.share', compact('shareLink', 'photo'));
    }

    public function search(Request $request)
    {
        $query = Photo::query();
        if ($request->keyword) {
            $query->where('tags', 'like', "%{$request->keyword}%");
        }
        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        // Additional filters can be added here, e.g., location, photographer
        $photos = $query->get();
        return view('photos.search', compact('photos'));
    }

    /**
     * Store a newly created photo (API).
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'license_type' => 'required|string|in:standard,extended,exclusive',
                'is_featured' => 'boolean',
                'tags' => 'nullable|string|max:500',
                'file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('media', time() . '_' . $file->getClientOriginalName(), 's3');

            $metadata = [];
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
                $exif = @exif_read_data($file->getRealPath());
                if ($exif) {
                    $metadata['date'] = $exif['DateTimeOriginal'] ?? null;
                    $metadata['location'] = $exif['GPSLatitude'] ?? null;
                }
            }

            $autoTags = 'face_detected,location_based';
            $tags = $validated['tags'] ? $validated['tags'] . ',' . $autoTags : $autoTags;

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

            $url = Storage::disk('s3')->url($path);
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
                'license_type' => 'required|string|in:standard,extended,exclusive',
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
                    $exif = @exif_read_data($file->getRealPath());
                    if ($exif) {
                        $metadata['date'] = $exif['DateTimeOriginal'] ?? null;
                        $metadata['location'] = $exif['GPSLatitude'] ?? null;
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