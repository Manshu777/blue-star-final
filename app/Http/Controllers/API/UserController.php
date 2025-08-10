<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Regusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        return Regusers::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'selfie_url' => 'nullable|url'
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = Regusers::create($validated);
        return response()->json($user, 201);
    }

    public function show($id) {
        return Regusers::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $user = Regusers::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$id,
            'selfie_url' => 'nullable|url'
        ]);
        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id) {
        Regusers::destroy($id);
        return response()->json(null, 204);
    }
}