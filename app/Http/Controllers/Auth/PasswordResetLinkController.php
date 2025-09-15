<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Cache;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
     public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Generate 6-digit OTP
        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in cache with email (expires in 10 minutes)
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        // Send OTP via email
        Mail::to($request->email)->send(new OtpMail($otp));

        return back()->with('status', 'OTP sent successfully.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if ($cachedOtp && $cachedOtp === $request->otp) {
            // OTP is valid, proceed with password reset
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Clear OTP from cache
            Cache::forget('otp_' . $request->email);

            return $status == Password::RESET_LINK_SENT
                ? redirect()->route('password.reset')->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['otp' => 'Failed to process password reset.']);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['otp' => 'Invalid OTP.']);
    }
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if ($cachedOtp && $cachedOtp === $request->otp) {
            // OTP is valid, update the user's password
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Clear OTP from cache
            Cache::forget('otp_' . $request->email);

            return redirect()->route('login')->with('status', 'Password reset successfully. Please log in with your new password.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['otp' => 'Invalid OTP.']);
    }
}
