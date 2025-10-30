@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Search Bar -->
            <div class="mb-8 relative">
                <form method="GET" action="{{ route('photos.search') }}">
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Keywords, tags, or face match"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-12">
                    <button type="submit"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Photos Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($photos as $photo)
                    <article
                        class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                        <div class="relative">
                            <img src="{{ Storage::disk('s3')->url($photo->watermarked_path ?? $photo->image_path) }}"
                                alt="{{ $photo->title }}"
                                class="w-full h-64 object-cover group-hover:scale-105 transition-transform">

                            @unless($photo->is_sold)
                                @include('components.watermark-overlay')
                            @endunless
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $photo->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($photo->description ?? '', 60) }}</p>

                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-indigo-600 font-semibold">${{ number_format($photo->price, 2) }}</span>

                                <div class="flex space-x-2">
                                    <a href="{{ route('photos.show', $photo->id) }}"
                                        class="text-sm bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-md transition-colors">View</a>
                                    <a href="{{ route('photos.edit', $photo->id) }}"
                                        class="text-sm bg-yellow-200 hover:bg-yellow-300 px-3 py-1 rounded-md transition-colors">Edit</a>
                                    <a href="{{ route('photos.share', $photo->id) }}"
                                        class="text-sm bg-blue-200 hover:bg-blue-300 px-3 py-1 rounded-md transition-colors">Share</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No photos found. Try a different search!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection