{{-- resources/views/user/photos/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Photo')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Heading --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Photo</h1>
            <a href="{{ route('photos.search') }}" class="text-sm text-blue-600 hover:underline">
                ← Back to Search
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Photo Preview --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="relative">
                    <img src="{{ $photo->url }}" alt="{{ $photo->title }}"
                        class="w-full max-h-[600px] object-contain bg-gray-100 dark:bg-gray-900">
                    {{-- Loading overlay (if needed) --}}
                    <div id="loadingOverlay" class="hidden absolute inset-0 bg-black/50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white animate-spin" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Editing Tools Panel --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Editing Tools</h2>

                {{-- Enhance Button --}}
                <form method="POST" action="{{ route('photos.enhance', $photo->id) }}" class="mb-4">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        AI Enhance Photo
                    </button>
                </form>

                {{-- AR Overlay --}}
                <form method="POST" action="{{ route('photos.ar_overlay', $photo->id) }}" enctype="multipart/form-data"
                    class="mb-4">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Apply AR Overlay</label>
                    <input type="file" name="overlay" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 
                               file:rounded-lg file:border-0 file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <button type="submit"
                        class="w-full mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Apply Overlay
                    </button>
                </form>

                {{-- Crop Tool --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Crop Photo</label>
                    <button type="button"
                        class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        Launch Crop Tool
                    </button>
                </div>

                {{-- Save Changes --}}
                <form method="POST" action="{{ route('photos.update', $photo->id) }}">
                    @csrf
                    @method('POST')
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo Title</label>
                    <input type="text" name="title" value="{{ $photo->title }}"
                        class="w-full px-3 py-2 mb-4 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ $photo->description }}</textarea>

                    <button type="submit"
                        class="w-full mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection