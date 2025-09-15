@extends('layouts.app')

@section('title', 'Home - Blue Star Memory')

@section('content')
    <section class="relative">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-600 text-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24 relative z-10">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                    Find and Relive Your <br />
                    <span class="bg-gradient-to-r from-blue-200 to-teal-200 bg-clip-text text-transparent">
                        Memories Instantly
                    </span>
                </h1>
                <p class="text-lg md:text-xl text-blue-100 max-w-2xl mb-10">
                    Organize, search, and experience your photos with AI-powered face recognition, AR filters, and secure
                    cloud storage.
                </p>

                <!-- Upload + Search Box -->

                <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-6 flex flex-col gap-4">
    <!-- Combined Upload and Search Form -->
    <div class="flex-1">
        <form id="uploadForm" method="POST" action="{{ route('photos.findMatches') }}" enctype="multipart/form-data" class="flex flex-col items-center bg-gray-50 rounded-xl px-5 py-4">
            @csrf
            <div class="flex items-center w-full">
                <svg class="h-6 w-6 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <label class="cursor-pointer flex-1">
                    <span class="text-gray-700 font-medium">Upload Selfie to Find Your Memories</span>
                    <input type="file" id="selfieInput" name="selfie" accept="image/jpeg,image/png" class="hidden" required />
                </label>
            </div>
            <!-- Image Preview -->
            <div id="previewContainer" class="hidden mt-4 w-32 h-32 bg-gray-100 rounded-lg overflow-hidden relative">
                <img id="previewImage" src="#" alt="Preview" class="w-full h-full object-cover" />
            </div>
            <!-- Crop and Submit Buttons -->
            <div class="flex gap-3 mt-4">
                <!-- <button type="button" id="cropButton" class="hidden bg-yellow-500 text-white font-medium rounded-lg px-4 py-2 hover:bg-yellow-600 transition">Crop</button> -->
                <button type="submit" id="submitButton" class="bg-blue-500 text-white font-medium rounded-lg px-4 py-2 hover:bg-blue-600 transition flex items-center">
                    <span id="submitText">Search</span>
                    <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 text-white ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
            @error('selfie')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

            </div>

            <!-- Decorative Background Glow -->
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-700 via-blue-600 to-purple-600 opacity-70"></div>
        </div>


        <!-- Features Section -->
        <section class="relative py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                    <!-- Feature Card -->
                    <div
                        class="text-center bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center shadow-inner">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Face Detection</h3>
                        <p class="text-gray-600 text-sm">Advanced AI technology to identify faces in photos.</p>
                    </div>

                    <!-- Feature Card -->
                    <div
                        class="text-center bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center shadow-inner">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 5h16M4 12h16M4 19h16" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Photo Grouping</h3>
                        <p class="text-gray-600 text-sm">Automatically organize photos by events and people.</p>
                    </div>

                    <!-- Feature Card -->
                    <div
                        class="text-center bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center shadow-inner">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9l9-7 9 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">AR Filters</h3>
                        <p class="text-gray-600 text-sm">Enhance your memories with immersive augmented reality filters.</p>
                    </div>

                    <!-- Feature Card -->
                    <div
                        class="text-center bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center shadow-inner">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a4 4 0 004-4M5 9h14M7 12h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure Cloud</h3>
                        <p class="text-gray-600 text-sm">Your memories are safe with enterprise-grade security.</p>
                    </div>
                </div>
            </div>
        </section>


        @include('components.how-it-works')
        @include('components.revolutionary')
        @include('components.gallery')
        @include('components.testimonials')
    </section>
@endsection