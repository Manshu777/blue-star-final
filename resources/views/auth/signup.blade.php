@extends('layouts.app')

@section('title', 'Register - Blue Star Memory')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="flex w-full max-w-7xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Left Side -->
            <div class="hidden md:flex md:w-1/2 relative">
                <img src="https://plus.unsplash.com/premium_photo-1689843658573-b1c234d46842?q=80&w=1200&auto=format&fit=crop"
                    alt="Background" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-[rgba(0,0,0,0.5)]"></div>
                <div class="z-10 flex flex-col items-center justify-center text-center px-8">
                    <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">Welcome to Blue Star Memory</h1>
                    <p class="mt-4 text-lg text-white opacity-90">Preserve your memories forever with us.</p>
                </div>
            </div>

            <!-- Right Side (Register Form) -->
            <div class="w-full md:w-1/2 px-10 py-20 flex items-center justify-center bg-gray-50">
                <div class="w-full bg-white p-10 rounded-2xl relative">
                    <!-- Decorative Glow -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-purple-500/20 rounded-full blur-3xl"></div>

                    <h2 class="text-3xl font-extrabold text-gray-800 mb-3">Create Account</h2>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-sm mb-6 shadow-md">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- OTP Verification Section (Hidden by default) -->
                    <div id="otp-section" class="hidden space-y-5">
                        <h3 class="text-xl font-semibold text-gray-800">Verify Email</h3>
                        <p class="text-sm text-gray-600">Enter the 6-digit OTP sent to your email</p>
                        <form id="otp-form" method="POST" action="{{ route('verify.otp') }}">
                            @csrf
                            <input type="hidden" name="email" id="otp-email">
                            <input type="text" name="otp" placeholder="Enter OTP" maxlength="6"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <button type="submit" id="verify-otp-btn"
                                class="w-full py-3 my-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition duration-300 ease-out">
                                Verify OTP
                            </button>
                        </form>
                        <p class="text-sm text-gray-500">
                            Didn't receive OTP? <a href="#" id="resend-otp" class="text-indigo-600 font-medium hover:underline">Resend</a>
                        </p>
                    </div>

                    <!-- Register Form -->
                    <form id="signup-form" method="POST" action="{{ route('signup.post') }}" class="space-y-5" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <input type="text" name="username" placeholder="Username (unique)" value="{{ old('username') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <!-- Reference Selfie Upload -->
                        <div class="space-y-2">
                            <label for="reference_selfie" class="block text-sm font-medium text-gray-700">
                                Upload Reference Selfie (Optional)
                            </label>
                            <p class="text-xs text-gray-500 mb-2">
                                Upload a clear photo of your face. This will be used for AI-powered facial recognition to automatically tag you in uploaded photos and videos. (JPEG/PNG, max 2MB)
                            </p>
                            <input type="file" name="reference_selfie" id="reference_selfie" accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('reference_selfie')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <input type="password" name="password" placeholder="Password" id="password"
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

                        <div class="relative">
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                id="password_confirmation"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                onclick="togglePassword('password_confirmation')">
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

                        <button type="submit" id="signup-btn"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] transition duration-300 ease-out relative">
                            <span id="signup-text">Sign Up</span>
                            <span id="loading-spinner" class="hidden absolute inset-0 flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <p class="text-sm text-gray-500 mb-8 mt-4">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const eye = input.parentElement.querySelector('.eye');
            const eyeSlash = input.parentElement.querySelector('.eye-slash');
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

        // Handle signup form submission with AJAX
        document.getElementById('signup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const signupBtn = document.getElementById('signup-btn');
            const signupText = document.getElementById('signup-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            const form = this;
            const formData = new FormData(form);

            signupBtn.disabled = true;
            signupText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');

            fetch('{{ route('signup.post') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                signupBtn.disabled = false;
                signupText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');

                if (data.success) {
                    // Show OTP section
                    document.getElementById('signup-form').classList.add('hidden');
                    document.getElementById('otp-section').classList.remove('hidden');
                    document.getElementById('otp-email').value = data.email;
                } else if (data.redirect) {
                    // Redirect to login if email exists
                    Swal.fire({
                        icon: 'info',
                        title: 'Email Already Registered',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    // Show validation errors
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: data.errors ? Object.values(data.errors).flat().join('<br>') : data.message,
                    });
                }
            })
            .catch(error => {
                signupBtn.disabled = false;
                signupText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                });
            });
        });

        // Handle OTP form submission with AJAX
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const verifyBtn = document.getElementById('verify-otp-btn');
            const form = this;
            const formData = new FormData(form);

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying...
                </span>`;

            fetch('{{ route('verify.otp') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verify OTP';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verify OTP';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                });
            });
        });

        // Handle resend OTP
        document.getElementById('resend-otp').addEventListener('click', function(e) {
            e.preventDefault();
            const email = document.getElementById('otp-email').value;

            fetch('{{ route('resend.otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'OTP Resent' : 'Error',
                    text: data.message,
                    timer: data.success ? 3000 : undefined,
                    showConfirmButton: !data.success
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to resend OTP. Please try again.',
                });
            });
        });
    </script>
@endsection