@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Your Stock Photos</h1>
            <a href="{{ route('photographer.upload') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">Upload New Photo</a>
        </div>
        @if(session('success')) <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div> @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($photos as $photo)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="relative">
                        <img src="{{ Storage::url($photo->display_path) }}" alt="{{ $photo->title }}" class="w-full h-48 object-cover">
                        @unless($photo->is_sold)
                            @include('components.watermark-overlay') {{-- Optional overlay --}}
                        @endunless
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $photo->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($photo->description, 80) }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">${{ number_format($photo->price, 2) }}</span>
                            @if($photo->is_sold) <span class="text-green-600 text-sm font-medium">Sold!</span> @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No photos yet. <a href="{{ route('photographer.upload') }}" class="text-blue-600 hover:underline">Upload your first one!</a></p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection