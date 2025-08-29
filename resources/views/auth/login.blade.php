@extends('layouts.app')

@section('title', 'Login - Blue Star Memory')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="flex w-full max-w-7xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Left Side -->
            <div class="hidden md:flex md:w-1/2 relative">
                <img src="https://plus.unsplash.com/premium_photo-1689843658573-b1c234d46842?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Background" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-[rgba(0,0,0,0.5)]"></div>
                <div class="z-10 flex flex-col items-center justify-center text-center px-8">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-lg">
                        Welcome Back!
                    </h1>
                    <p class="mt-4 text-lg text-gray-200 max-w-sm">
                        Log in and continue preserving your memories with Blue Star Memory.
                    </p>
                </div>
            </div>

            <!-- Right Side (Login Form) -->
            <div class="w-full md:w-1/2 p-10 flex items-center justify-center bg-white">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Sign in to your account</h2>
                    <p class="text-sm text-gray-500 mb-6">
                        Don’t have an account?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Register</a>
                    </p>

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

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Password -->
                        <div>
                            <input type="password" name="password" placeholder="Password"
                                class="w-full px-5 py-3 border rounded-full shadow-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 rounded-full hover:bg-indigo-700 shadow-lg transition font-medium">
                            Log In
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 my-6">
                        <div class="flex-grow h-px bg-gray-200"></div>
                        <span class="text-gray-400 text-sm">or</span>
                        <div class="flex-grow h-px bg-gray-200"></div>
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

                    <!-- Forgot Password -->
                    <p class="mt-6 text-center text-sm">
                        <a href="{{ route('password.request') }}" class="text-gray-500 hover:underline">
                            Forgot your password?
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection