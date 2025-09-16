@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-8">
            <a href="{{ route('shop.index') }}" class="text-blue-600 hover:underline">Shop</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="{{ route('shop.index', ['category' => $product->category->id]) }}"
                class="text-blue-600 hover:underline">
                {{ $product->category->name }}
            </a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-700 font-medium">{{ $product->name }}</span>
        </nav>

        <!-- Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Image / Video -->
            <div class="bg-white rounded-2xl shadow-lg p-4 flex items-center justify-center relative group">
                @if ($product->type === 'photo' || $product->type === 'merchandise')
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full max-h-[500px] object-contain rounded-lg transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                        onclick="zoomImage(this)">
                @elseif ($product->type === 'video')
                    <video src="{{ $product->preview_url }}" class="w-full max-h-[500px] rounded-lg shadow-md" controls></video>
                @endif
            </div>

            <!-- Details -->
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                <p class="text-3xl font-bold text-green-600 mb-6">${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-700 leading-relaxed mb-8">{{ $product->description }}</p>

                <!-- Add to Cart -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-10">
                    @csrf
                    <div class="flex items-center gap-4 mb-6">
                        <label for="quantity" class="font-semibold text-gray-700">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit"
                        class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-semibold shadow hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                        🛒 Add to Cart
                    </button>
                </form>

                <!-- Tabs -->
                <div x-data="{ tab: 'description' }" class="mt-8">
                    <ul class="flex border-b mb-4">
                        <li class="mr-2">
                            <button @click="tab = 'description'"
                                :class="tab === 'description' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                                class="inline-block py-2 px-5 border-b-2 font-semibold transition">
                                Description
                            </button>
                        </li>
                        <li>
                            <button @click="tab = 'reviews'"
                                :class="tab === 'reviews' ? 'border-blue-600 text-blue-600' : 'text-gray-500'"
                                class="inline-block py-2 px-5 border-b-2 font-semibold transition">
                                Reviews
                            </button>
                        </li>
                    </ul>

                    <div x-show="tab === 'description'" class="p-5 bg-gray-50 rounded-lg shadow-inner text-gray-700">
                        {{ $product->description }}
                    </div>
                    <div x-show="tab === 'reviews'" class="p-5 bg-gray-50 rounded-lg shadow-inner text-gray-700">
                        <p>No reviews yet. Be the first to review!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-20">
            <h2 class="text-2xl font-bold mb-8 text-gray-900">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('products.show', $related->slug) }}"
                        class="bg-white rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}"
                            class="w-full h-48 object-cover rounded-t-xl">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $related->name }}</h3>
                            <p class="text-green-600 font-medium mt-1">${{ number_format($related->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Suggested Products -->
        <div class="mt-20">
            <h2 class="text-2xl font-bold mb-8 text-gray-900">You Might Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($suggestedProducts as $suggested)
                    <a href="{{ route('products.show', $suggested->slug) }}"
                        class="bg-white rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                        <img src="{{ Storage::url($suggested->image) }}" alt="{{ $suggested->name }}"
                            class="w-full h-48 object-cover rounded-t-xl">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $suggested->name }}</h3>
                            <p class="text-green-600 font-medium mt-1">${{ number_format($suggested->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Simple Zoom Feature (popup) -->
    <script>
        function zoomImage(img) {
            const overlay = document.createElement('div');
            overlay.className = "fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50";
            overlay.innerHTML = `<img src="${img.src}" class="max-h-[90%] max-w-[90%] rounded-lg shadow-2xl">`;
            overlay.onclick = () => overlay.remove();
            document.body.appendChild(overlay);
        }
    </script>
@endsection