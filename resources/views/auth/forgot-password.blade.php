@extends('layouts.app')

@section('title', 'Forgot Password - Blue Star Memory')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="flex w-full max-w-7xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Left Side -->
            <div class="hidden md:flex md:w-1/2 relative">
                <img src="https://plus.unsplash.com/premium_photo-1689843658573-b1c234d46842?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGxufDB8fHx8fA%3D%3D"
                    alt="Background" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-[rgba(0,0,0,0.5)]"></div>
                <div class="z-10 flex flex-col items-center justify-center text-center px-8">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-lg">
                        Reset Your Password
                    </h1>
                    <p class="mt-4 text-lg text-gray-200 max-w-sm">
                        Enter your email to receive a one-time password, then set your new password.
                    </p>
                </div>
            </div>

            <!-- Right Side (Forgot Password Form) -->
            <div class="w-full md:w-1/2 px-10 py-44 flex items-center justify-center bg-white">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Your Password</h2>

                    <!-- Status Message -->
                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-md text-sm mb-5 shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md text-sm mb-5 shadow-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Email Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" id="email-form">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition duration-300 ease-out">
                            Send OTP
                        </button>
                    </form>

                    <!-- OTP and Password Reset Form (Hidden Initially) -->
                    <form method="POST" action="{{ route('password.update-otp') }}" class="space-y-5 hidden" id="otp-form">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        <input type="text" name="otp" placeholder="Enter OTP"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        <div class="relative">
                            <input type="password" name="password" placeholder="New Password" id="password"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                onclick="togglePassword('password')">
                                <svg class="h-5 w-5 text-gray-500 eye" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="h-5 w-5 text-gray-500 eye-slash hidden" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0111.25 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <input type="password" name="password_confirmation" placeholder="Confirm New Password"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition duration-300 ease-out">
                            Reset Password
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Back to Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const eye = button.querySelector('.eye');
            const eyeSlash = button.querySelector('.eye-slash');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeSlash.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeSlash.classList.add('hidden');
            }
        }

        // Show OTP form after successful email submission
        @if (session('status') == 'OTP sent successfully.')
            document.getElementById('email-form').classList.add('hidden');
            document.getElementById('otp-form').classList.remove('hidden');
        @endif
    </script>
@endsection