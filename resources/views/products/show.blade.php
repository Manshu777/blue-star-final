@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('shop.index') }}" class="text-blue-500 hover:underline">Shop</a> / 
        <a href="{{ route('shop.index', ['category' => $product->category->id]) }}" class="text-blue-500 hover:underline">{{ $product->category->name }}</a> / 
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image/Video -->
        <div class="bg-white rounded-lg shadow-md p-4">
            @if ($product->type === 'photo' || $product->type === 'merchandise')
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-contain cursor-zoom-in" onclick="zoomImage(this)">
            @elseif ($product->type === 'video')
                <video src="{{ $product->preview_url }}" class="w-full h-auto" controls></video>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
            <p class="text-2xl font-semibold text-green-600 mb-4">${{ number_format($product->price, 2) }}</p>
            <p class="text-gray-600 mb-6">{{ $product->description }}</p>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="flex items-center mb-4">
                    <label for="quantity" class="mr-4">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="border rounded px-2 py-1 w-16">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">Add to Cart</button>
            </form>

            <!-- Tabs for Description, Reviews, etc. -->
            <div class="tabs">
                <ul class="flex border-b">
                    <li class="mr-1"><a href="#description" class="bg-white inline-block py-2 px-4 text-blue-500 font-semibold">Description</a></li>
                    <li class="mr-1"><a href="#reviews" class="bg-white inline-block py-2 px-4 text-blue-500 font-semibold">Reviews</a></li>
                </ul>
                <div id="description" class="p-4">{{ $product->description }}</div>
                <div id="reviews" class="p-4">No reviews yet. Be the first!</div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($relatedProducts as $related)
                <div class="bg-white rounded-lg shadow-md">
                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">{{ $related->name }}</h3>
                        <p>${{ number_format($related->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Suggested Products -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Suggested Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($suggestedProducts as $suggested)
                <div class="bg-white rounded-lg shadow-md">
                    <img src="{{ asset('storage/' . $suggested->image) }}" alt="{{ $suggested->name }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">{{ $suggested->name }}</h3>
                        <p>${{ number_format($suggested->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- JavaScript for Image Zoom (simple) -->
    <script>
        function zoomImage(img) {
            
            alert('Zoom feature placeholder');
        }
    </script>
</div>
@endsection