<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateSelfie(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $file = $request->file('selfie');
        $path = $file->store('selfies', 's3');
        $user->reference_selfie_path = $path;
        $user->save();

        return redirect()->route('profile')->with('success', 'Selfie updated successfully.');
    }
}