<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotographerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('photographer'); // We'll register this middleware next
    }

    /**
     * Dashboard: List user's photos for sale.
     */
    public function index()
    {
        $photos = Auth::user()->photos()->with('photographer')->latest()->get();
        return view('photographer.dashboard', compact('photos'));
    }

    /**
     * Upload new photo for sale.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'price' => 'required|numeric|min:0.01',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Store original
        $originalPath = $request->file('image')->store('photos/originals', 'public');
        $photo = Photo::create([
            'title' => $request->title,
            'description' => $request->description,
            'photographer_id' => Auth::id(),
            'price' => $request->price,
            'original_path' => $originalPath,
        ]);

        // Generate watermarked version
        $watermarkPath = str_replace('originals', 'watermarked', $originalPath);
        $photo->watermarked_path = $watermarkPath;
        $photo->save();

        if (!$photo->generateWatermark(storage_path('app/public/' . $originalPath))) {
            return back()->withErrors(['image' => 'Watermark generation failed.']);
        }

        // Save watermarked (generateWatermark handles this)
        return redirect()->route('photographer.dashboard')->with('success', 'Photo uploaded and watermarked!');
    }
}