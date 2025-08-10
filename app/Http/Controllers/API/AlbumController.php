<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller {
    public function index() {
        return Album::with('user')->get();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'is_shared' => 'boolean'
        ]);
        $album = Album::create($validated);
        return response()->json($album, 201);
    }
}