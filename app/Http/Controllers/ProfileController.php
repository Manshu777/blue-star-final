<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

     public function index()
    {
        // Placeholder for dashboard data (e.g., recent photos or user info)
        return view('user.dashboard.index');
    }


     /**
     * Handle general photo search.
     */
    public function search(Request $request)
    {
        // Validate search query
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        // Placeholder for search logic
        $query = $request->input('query');
        // Implement search logic here (e.g., query database for photos)
        $photos = []; // Replace with actual search results

        return view('photos.search', compact('photos', 'query'));
    }

    /**
     * Handle facial recognition-based photo search.
     */
    public function facialSearch(Request $request)
    {
        // Validate uploaded image for facial search
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Placeholder for facial recognition logic
        // Process the uploaded image and find matching photos
        $photos = []; // Replace with actual facial search results

        return view('photos.search', compact('photos'));
    }

    /**
     * Display a photo preview.
     */
    public function preview($id)
    {
        // Placeholder for fetching photo by ID
        $photo = null; // Replace with actual photo retrieval logic

        if (!$photo) {
            abort(404, 'Photo not found');
        }

        return view('photos.preview', compact('photo'));
    }


    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
