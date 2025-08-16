<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use Intervention\Image\ImageManagerStatic as Image;
use Rembg\Rembg;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = auth()->user()->photos()->with('collaborators')->get();
        return view('upload', compact('photos'));
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:jpg,jpeg,png|max:2048']);
        $path = $request->file('file')->store('photos', 's3');
        $url = Storage::disk('s3')->url($path);

        $image = Image::make($request->file('file'));
        $exif = $image->exif();
        $tags = [
            'date' => $exif['DateTimeOriginal'] ?? now()->toDateTimeString(),
            'location' => $exif['GPSLatitude'] ?? null,
            'faces' => 0
        ];

        $photo = Photo::create([
            'user_id' => auth()->id(),
            'url' => $url,
            'tags' => $tags,
            'is_active_for_merch' => false
        ]);

        return response()->json(['success' => true, 'url' => $url]);
    }

    public function sharpen(Request $request)
    {
        $image = Image::make(Storage::disk('s3')->get($request->image));
        $image->sharpen(10);
        $path = 'photos/edited_' . time() . '.png';
        Storage::disk('s3')->put($path, $image->encode());
        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    }

    public function colorCorrect(Request $request)
    {
        $image = Image::make(Storage::disk('s3')->get($request->image));
        $image->contrast(10)->brightness(10);
        $path = 'photos/edited_' . time() . '.png';
        Storage::disk('s3')->put($path, $image->encode());
        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    }

    public function removeBackground(Request $request)
    {
        $inputPath = Storage::disk('s3')->get($request->image);
        $output = Rembg::remove($inputPath);
        $path = 'photos/edited_' . time() . '.png';
        Storage::disk('s3')->put($path, $output);
        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    }

    public function save(Request $request)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
        $path = 'photos/saved_' . time() . '.png';
        Storage::disk('s3')->put($path, $imageData);
        $photo = Photo::findOrFail($request->photo_id);
        $photo->update(['url' => Storage::disk('s3')->url($path)]);
        return response()->json(['success' => true]);
    }

    public function share(Request $request)
    {
        $photo = Photo::findOrFail($request->photo_id);
        $collaborator = \App\Models\User::where('email', $request->email)->first();
        if ($collaborator) {
            $photo->collaborators()->syncWithoutDetaching([$collaborator->id]);
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'User not found'], 404);
    }

    public function updateTags(Request $request, Photo $photo)
    {
        $request->validate(['tags' => 'required|json']);
        $photo->update(['tags' => json_decode($request->tags, true)]);
        return redirect()->route('photos.index')->with('success', 'Tags updated successfully');
    }

    public function delete(Photo $photo)
    {
        Storage::disk('s3')->delete(parse_url($photo->url, PHP_URL_PATH));
        $photo->delete();
        return redirect()->route('photos.index')->with('success', 'Photo deleted successfully');
    }

    public function toggleMerch(Request $request)
    {
        $request->validate(['photo_id' => 'required|exists:photos,id']);
        $photo = Photo::findOrFail($request->photo_id);
        $photo->update(['is_active_for_merch' => !$photo->is_active_for_merch]);
        return response()->json(['success' => true, 'is_active' => $photo->is_active_for_merch]);
    }
}