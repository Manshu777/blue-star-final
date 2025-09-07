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
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
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
        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already registered. Please login or use a different email.',
                'redirect' => route('login')
            ], 422);
        }

        try {
            $request->validate([
                'username' => 'required|string|max:50|unique:users,username',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'reference_selfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Ensure temp/selfies directory exists
        $directory = 'temp/selfies';
        if (!Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->makeDirectory($directory);
        }

        // Handle selfie upload to temporary storage if provided
        $selfieTempPath = null;
        if ($request->hasFile('reference_selfie')) {
            $file = $request->file('reference_selfie');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $fileExtension;

            // Store temporarily on local disk
            $selfieTempPath = $file->storeAs($directory, $fileName, 'local');
            if (!$selfieTempPath || !Storage::disk('local')->exists($selfieTempPath)) {
                Log::error('Temporary selfie upload failed', ['username' => $request->username, 'path' => $selfieTempPath]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload reference selfie.'
                ], 500);
            }
            Log::info('Selfie temporarily stored', ['path' => $selfieTempPath]);
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        // Create user with pending status
        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'temp_selfie_path' => $selfieTempPath,
                'status' => 'pending',
                'plan_id' => 1,
            ]);
        } catch (\Exception $e) {
            // Delete temporary file if user creation fails
            if ($selfieTempPath && Storage::disk('local')->exists($selfieTempPath)) {
                Storage::disk('local')->delete($selfieTempPath);
            }
            Log::error('User creation failed', ['email' => $request->email, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user.'
            ], 500);
        }

        // Send OTP email
        try {
            Mail::to($request->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            // Delete temporary file and user if email sending fails
            if ($selfieTempPath && Storage::disk('local')->exists($selfieTempPath)) {
                Storage::disk('local')->delete($selfieTempPath);
            }
            $user->delete();
            Log::error('Failed to send OTP email', ['email' => $request->email, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }

        // Return success response to show OTP section
        return response()->json([
            'success' => true,
            'email' => $request->email,
            'message' => 'OTP sent to your email.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|digits:6',
                'email' => 'required|email',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }

        // Find user by email and OTP
        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('status', 'pending')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP or email. Please try again.'
            ], 422);
        }

        // Handle selfie upload to S3 if exists
        $selfiePath = null;
        if ($user->temp_selfie_path) {
            if (!Storage::disk('local')->exists($user->temp_selfie_path)) {
                Log::error('Temporary selfie file not found', ['path' => $user->temp_selfie_path, 'username' => $user->username]);
                $user->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Temporary selfie file not found. Please try signing up again.'
                ], 500);
            }

            $fileExtension = pathinfo($user->temp_selfie_path, PATHINFO_EXTENSION);
            $fileName = Str::random(40) . '.' . $fileExtension;
            $directory = 'selfies';

            try {
                // Upload to S3
                $selfiePath = Storage::disk('s3')->putFileAs($directory, new \Illuminate\Http\UploadedFile(
                    storage_path('app/' . $user->temp_selfie_path),
                    $fileName
                ), $fileName);

                if (!$selfiePath) {
                    Log::error('S3 selfie upload failed', ['username' => $user->username]);
                    $user->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload reference selfie to S3.'
                    ], 500);
                }

                // Delete temporary file
                Storage::disk('local')->delete($user->temp_selfie_path);
                Log::info('Selfie uploaded to S3 and temporary file deleted', ['s3_path' => $selfiePath]);
            } catch (\Exception $e) {
                Log::error('S3 upload exception', ['error' => $e->getMessage(), 'username' => $user->username]);
                $user->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload selfie to S3: ' . $e->getMessage()
                ], 500);
            }
        }

        try {
            // Update user to verified status
            $user->update([
                'otp' => null,
                'temp_selfie_path' => null,
                'reference_selfie_path' => $selfiePath,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            Auth::login($user);

            // Return JSON response for SweetAlert
            return response()->json([
                'success' => true,
                'message' => 'Signup successful! You are now logged in.',
                'redirect' => route('upload')
            ]);
        } catch (\Exception $e) {
            Log::error('User update failed', ['error' => $e->getMessage(), 'username' => $user->username]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to finalize user signup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email.',
                'errors' => $e->errors()
            ], 422);
        }

        // Find pending user
        $user = User::where('email', $request->email)
                    ->where('status', 'pending')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No pending signup found for this email.'
            ], 422);
        }

        // Generate new OTP
        $otp = random_int(100000, 999999);
        $user->update(['otp' => $otp]);

        try {
            Mail::to($request->email)->send(new OtpMail($otp));
            return response()->json(['success' => true, 'message' => 'OTP resent successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP email', ['email' => $request->email, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to resend OTP'], 500);
        }
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
        try {
            $credentials = $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
                'remember' => 'boolean', // Optional remember me checkbox
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->onlyInput('login');
        }

        // Determine if login is email or username
        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Check if user exists and is active
        $user = User::where($fieldType, $credentials['login'])->first();
        if (!$user) {
            Log::warning('Login attempt failed: User not found', ['login' => $credentials['login']]);
            return $this->failedLoginResponse($request, 'Invalid username/email or password.');
        }

        if ($user->status !== 'active') {
            Log::warning('Login attempt failed: User not active', [
                'login' => $credentials['login'],
                'status' => $user->status
            ]);
            return $this->failedLoginResponse($request, 'Your account is not verified. Please complete OTP verification.');
        }

        // Attempt authentication
        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $request->input('remember', false))) {
            try {
                $request->session()->regenerate();
                Log::info('User logged in successfully', ['user_id' => Auth::id(), 'login' => $credentials['login']]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Logged in successfully.',
                        'redirect' => route('upload')
                    ]);
                }
                return redirect()->route('upload')->with('success', 'Logged in successfully.');
            } catch (\Exception $e) {
                Log::error('Session regeneration failed', [
                    'error' => $e->getMessage(),
                    'login' => $credentials['login']
                ]);
                Auth::logout();
                return $this->failedLoginResponse($request, 'Failed to initialize session. Please try again.');
            }
        }

        Log::warning('Login attempt failed: Invalid credentials', ['login' => $credentials['login']]);
        return $this->failedLoginResponse($request, 'Invalid username/email or password.');
    }


    /**
     * Return a failed login response based on request type (JSON or redirect)
     */
    protected function failedLoginResponse(Request $request, $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 401);
        }
        return back()->withErrors([
            'login' => $message
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
