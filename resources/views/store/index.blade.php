
 @extends('layouts.app')

@section('title', 'Home - Blue Star Memory')

@section('content')   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Featured Photos Section -->
                    <h3 class="text-lg font-semibold mb-4">Featured Photos</h3>
                    @if ($featuredPhotos->isEmpty())
                        <p class="text-gray-500">No featured photos available.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($featuredPhotos as $photo)
                                <div class="border rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->title }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h4 class="text-md font-medium">{{ $photo->title }}</h4>
                                        <p class="text-gray-600">${{ number_format($photo->price, 2) }}</p>
                                        <form action="{{ route('store.purchase_photo', $photo->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <select name="license_type" class="w-full p-2 border rounded mb-2">
                                                <option value="personal">Personal Use</option>
                                                <option value="commercial">Commercial Use</option>
                                            </select>
                                            <select name="payment_method" class="w-full p-2 border rounded mb-2">
                                                <option value="card">Credit Card</option>
                                                <option value="paypal">PayPal</option>
                                            </select>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Purchase</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Featured Merchandise Section -->
                    <h3 class="text-lg font-semibold mt-8 mb-4">Featured Merchandise</h3>
                    @if ($featuredMerchandise->isEmpty())
                        <p class="text-gray-500">No featured merchandise available.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($featuredMerchandise as $merchandise)
                                <div class="border rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ asset($merchandise->image_path ?? 'images/placeholder.jpg') }}" alt="{{ $merchandise->name }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h4 class="text-md font-medium">{{ $merchandise->name }}</h4>
                                        <p class="text-gray-600">${{ number_format($merchandise->price, 2) }}</p>
                                        <a href="{{ route('store.merchandise') }}" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View & Customize</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection