{{-- resources/views/user/photos/search.blade.php --}}
@extends('layouts.app')

@section('title', 'Search Photos')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Page Heading --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Search Photos</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Filters Sidebar --}}
        <aside class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Filters</h2>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('photos.search') }}" class="mb-4">
                <input type="text" name="q" placeholder="Search by keyword..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    value="{{ request('q') }}">
            </form>

            {{-- Date Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input type="date" name="date"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Tag Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
                <select name="tag"
                    class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">All</option>
                    <option value="nature">Nature</option>
                    <option value="portrait">Portrait</option>
                    <option value="event">Event</option>
                </select>
            </div>

            {{-- Facial Recognition Search --}}
            <form method="POST" action="{{ route('photos.facial_search') }}" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Search by Face
                </label>
                <input type="file" name="face" accept="image/*"
                    class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 
                           file:rounded-lg file:border-0 file:text-sm file:font-semibold
                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit"
                    class="w-full mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Search Face
                </button>
            </form>
        </aside>

        {{-- Photo Results --}}
        <section class="lg:col-span-3">
            @if($photos->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 4h18" />
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">No photos found. Try adjusting your filters.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($photos as $photo)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 truncate">
                                    {{ $photo->title }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $photo->created_at->diffForHumans() }}</p>
                                <div class="mt-3 flex space-x-2">
                                    <a href="{{ route('photos.preview', $photo->id) }}"
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                        Preview
                                    </a>
                                    <a href="{{ route('photos.edit', $photo->id) }}"
                                        class="px-3 py-1 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $photos->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
