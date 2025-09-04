<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class AuthController extends Controller
{


     public function __construct()
    {
        $this->s3 = new S3Client([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $this->rekognition = new RekognitionClient([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION', 'eu-west-1'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }



   public function showSignupForm()
{
    return view('auth.signup');
}


  public function signup(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:50|unique:users,username',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'reference_selfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Handle selfie upload to S3 if provided
    $selfiePath = null;
    if ($request->hasFile('reference_selfie')) {
        $file = $request->file('reference_selfie');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $fileExtension;
        $directory = 'selfies';

        // Upload to S3
        $selfiePath = Storage::disk('s3')->putFileAs($directory, $file, $fileName);
        if (!$selfiePath) {
            Log::error('S3 selfie upload failed', ['username' => $request->username]);
            return redirect()->back()->with('error', 'Failed to upload reference selfie.')->withInput();
        }
    }
    Log::info('Selfie uploaded to S3', ['path' => $selfiePath]);

    $user = User::create([
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'reference_selfie_path' => $selfiePath, // Save S3 path (e.g., selfies/<random-string>.jpg)
        'plan_id' => 1, // Default plan
    ]);

    Auth::login($user);

    return redirect()->route('upload')->with('success', 'Signup successful! You are now logged in.');
}

    /**
     * Show login form (optional for web)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string', 
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('upload')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'login' => 'Invalid username/email or password.',
        ])->onlyInput('login');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
