<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EditController extends Controller
{
    public function edit(Photo $photo, Request $request)
    {
        $image = Image::make(Storage::disk('s3')->get($photo->image_path));

        // Example edits; expand based on form inputs
        if ($request->has('crop')) {
            // Parse crop params, e.g., $image->crop(width, height, x, y);
            // For simplicity, assume fixed crop
            $image->crop(100, 100, 0, 0);
        }
        if ($request->has('sharpen')) {
            $image->sharpen(10);
        }
        if ($request->has('brightness')) {
            $image->brightness($request->brightness);
        }
        if ($request->has('overlay')) {
            $image->text($request->overlay, 10, 10, function ($font) {
                $font->size(20);
                $font->color('#ffffff');
            });
        }

        $newPath = 'edited/' . Str::random(40) . '.jpg';
        Storage::disk('s3')->put($newPath, $image->encode());

        $photo->image_path = $newPath;
        $photo->save();

        return redirect()->route('photos.show', $photo)->with('success', 'Photo edited successfully.');
    }
}