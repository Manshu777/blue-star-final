

 @extends('layouts.app')

@section('title', 'Home - Blue Star Memory')

@section('content') 


    <div class="container h-screen mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Shop Our Mugs</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($mugs as $mug)
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <img src="{{ Storage::url($mug->image_path) }}" alt="{{ $mug->name }}" class="w-full h-48 object-cover rounded mb-4">
                    <h2 class="text-xl font-semibold">{{ $mug->name }}</h2>
                    <p class="text-gray-600">{{ $mug->description }}</p>
                    <p class="text-lg font-bold mt-2">${{ number_format($mug->price, 2) }}</p>
                    <p class="text-sm text-gray-500">Stock: {{ $mug->stock }}</p>
                    @if ($mug->is_featured)
                        <span class="inline-block bg-yellow-200 text-yellow-800 text-xs px-2 py-1 rounded mt-2">Featured</span>
                    @endif
                    @auth
                        <a href="{{ route('shop.customize', $mug->id) }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Customize</a>
                    @else
                        <p class="mt-4 text-sm text-red-500">Please log in to customize</p>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
@endsection