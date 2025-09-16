@extends('layouts.app')

@section('title', 'Shop - Blue Star Memory')

@section('content')
    <div class="container h-screen mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Shop Our Products</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover rounded mb-4">
                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">{{ $product->description }}</p>
                    <p class="text-lg font-bold mt-2">${{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-500">Type: {{ ucfirst($product->type) }}</p>

                    <a href="{{ route('products.show', $product->slug) }}"
                        class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        View Details
                    </a>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No products available.</p>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
