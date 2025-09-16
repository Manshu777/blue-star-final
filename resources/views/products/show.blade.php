@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <a href="{{ route('shop.index') }}" class="text-blue-500 hover:underline">Shop</a> /
            <a href="{{ route('shop.index', ['category' => $product->category->id]) }}"
                class="text-blue-500 hover:underline">
                {{ $product->category->name }}
            </a> /
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        <!-- Product Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Image/Video -->
            <div class="bg-white rounded-lg shadow p-4">
                @if ($product->type === 'photo' || $product->type === 'merchandise')
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-auto object-contain rounded cursor-zoom-in" onclick="zoomImage(this)">
                @elseif ($product->type === 'video')
                    <video src="{{ $product->preview_url }}" class="w-full h-auto rounded" controls></video>
                @endif
            </div>

            <!-- Details -->
            <div>
                <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
                <p class="text-2xl font-semibold text-green-600 mb-4">${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                <!-- Add to Cart -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex items-center mb-4">
                        <label for="quantity" class="mr-4 font-medium">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="border rounded px-3 py-2 w-20">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                        Add to Cart
                    </button>
                </form>

                <!-- Tabs -->
                <div x-data="{ tab: 'description' }" class="mt-6">
                    <ul class="flex border-b mb-4">
                        <li class="mr-1">
                            <button @click="tab = 'description'"
                                :class="tab === 'description' ? 'border-blue-500 text-blue-600' : 'text-gray-500'"
                                class="inline-block py-2 px-4 border-b-2 font-semibold">
                                Description
                            </button>
                        </li>
                        <li class="mr-1">
                            <button @click="tab = 'reviews'"
                                :class="tab === 'reviews' ? 'border-blue-500 text-blue-600' : 'text-gray-500'"
                                class="inline-block py-2 px-4 border-b-2 font-semibold">
                                Reviews
                            </button>
                        </li>
                    </ul>
                    <div x-show="tab === 'description'" class="p-4 bg-gray-50 rounded">
                        {{ $product->description }}
                    </div>
                    <div x-show="tab === 'reviews'" class="p-4 bg-gray-50 rounded">
                        No reviews yet. Be the first!
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('products.show', $related->slug) }}"
                        class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}"
                            class="w-full h-40 object-cover rounded-t">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800">{{ $related->name }}</h3>
                            <p class="text-green-600 font-medium">${{ number_format($related->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Suggested Products -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Suggested Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($suggestedProducts as $suggested)
                    <a href="{{ route('products.show', $suggested->slug) }}"
                        class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <img src="{{ Storage::url($suggested->image) }}" alt="{{ $suggested->name }}"
                            class="w-full h-40 object-cover rounded-t">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800">{{ $suggested->name }}</h3>
                            <p class="text-green-600 font-medium">${{ number_format($suggested->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- JS for zoom (placeholder) -->
    <script>
        function zoomImage(img) {
            alert('Zoom feature coming soon!');
        }
    </script>
@endsection