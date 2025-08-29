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
            <div class="w-full md:w-1/2 p-10 flex items-center justify-center bg-gray-50">
                <div class="w-full  bg-white p-10 rounded-2xl  relative">

                    <!-- Decorative Glow -->
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-purple-500/20 rounded-full blur-3xl"></div>

                    <h2 class="text-3xl font-extrabold text-gray-800 mb-3">Create Account</h2>
                    <p class="text-sm text-gray-500 mb-8">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Log in</a>
                    </p>

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

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <input type="password" name="password" placeholder="Password"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <input type="password" name="password_confirmation" placeholder="Confirm Password"
                            class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 
                                   text-white font-semibold shadow-lg hover:shadow-xl 
                                   hover:scale-[1.02] transition duration-300 ease-out">
                            Sign Up
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 my-6">
                        <div class="flex-grow h-px bg-gray-500"></div>
                        <span class="text-gray-500 text-sm">Or sign up with</span>
                        <div class="flex-grow h-px bg-gray-500"></div>
                    </div>


                    <!-- Social Login -->
                    <div class="space-y-3">
                        <a href="#"
                            class="w-full flex items-center justify-center gap-2 py-2.5 border rounded-full bg-white shadow-sm hover:bg-gray-50 transition">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
                            <span class="text-sm font-medium text-gray-700">Continue with Google</span>
                        </a>

                        <a href="#"
                            class="w-full flex items-center justify-center gap-2 py-2.5 border border-blue-600 rounded-full bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition">
                            <img src="https://www.svgrepo.com/show/349574/facebook.svg" class="w-5 h-5 invert"
                                alt="Facebook">
                            <span class="text-sm font-medium">Continue with Facebook</span>
                        </a>

                        <a href="#"
                            class="w-full flex items-center justify-center gap-2 py-2.5 border rounded-full bg-gray-900 text-white shadow-sm hover:bg-gray-800 transition">
                            <img src="https://www.svgrepo.com/show/512317/github-142.svg" class="w-5 h-5 invert"
                                alt="GitHub">
                            <span class="text-sm font-medium">Continue with GitHub</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection