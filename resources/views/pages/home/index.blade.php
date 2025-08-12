@extends('layouts.app')

@section('title', 'Home - Blue Star Memory')

@section('content')
    <section class="relative">
        <div class="bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 text-white py-20 mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl text-left mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-8">
                    Find and Relive Your<br />
                    <span class="text-blue-100">Memories Instantly</span>
                </h1>
                
                <div class="max-w-8xl mx-auto bg-white rounded-2xl shadow-2xl p-4 flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <a  class="flex items-center bg-gray-50 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                            <svg class="h-5 w-5 text-gray-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="text-gray-600 font-medium">Upload Selfie to Find Your Memories</span>
                        </a>
                    </div>
                    
                    <div class="flex-1 relative">
                        <form  method="GET" class="flex items-center bg-gray-50 rounded-xl">
                            <svg class="h-5 w-5 text-gray-400 ml-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                name="query"
                                placeholder="Search by Date/Location"
                                class="w-full px-4 py-3 bg-transparent text-gray-700 placeholder-gray-500 focus:outline-none"
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <section class="py-8 lg:py-16 w-full absolute top-[283px] left-0 ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                        <div class="bg-blue-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Face Detection</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Advanced AI technology to identify faces in photos</p>
                    </div>
                    <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                        <div class="bg-blue-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 12h16M4 19h16" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Photo Grouping</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Automatically organize photos by events and people</p>
                    </div>
                    <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                        <div class="bg-blue-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">AR Filters</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Enhance your memories with augmented reality</p>
                    </div>
                    <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                        <div class="bg-blue-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <svg class="h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a4 4 0 004-4M5 9h14M7 12h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure Cloud</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Your memories are safe with enterprise-grade security</p>
                    </div>
                </div>
            </div>
        </section>

        @include('components.how-it-works')
         @include('components.gallery')
          @include('components.testimonials')
    </section>
@endsection