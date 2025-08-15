
 @extends('layouts.app')

@section('title', 'Home - Blue Star Memory')

@section('content') 

      
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Merchandise Catalog
        </h2>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($merchandise->isEmpty())
                        <p class="text-gray-500">No merchandise available.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($merchandise as $item)
                                <div class="border rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ asset($item->image_path ?? 'images/placeholder.jpg') }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h4 class="text-md font-medium">{{ $item->name }}</h4>
                                        <p class="text-gray-600">${{ number_format($item->price, 2) }}</p>
                                        <p class="text-gray-500 text-sm">{{ $item->description }}</p>
                                        <p class="text-gray-500 text-sm">In Stock: {{ $item->stock }}</p>
                                        <form action="{{ route('store.purchase_merchandise') }}" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="merchandise_id" value="{{ $item->id }}">
                                            <div class="mb-2">
                                                <label for="quantity-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Quantity</label>
                                                <input type="number" name="quantity" id="quantity-{{ $item->id }}" value="1" min="1" max="{{ $item->stock }}" class="w-24 p-2 border rounded">
                                            </div>
                                            <div class="mb-2">
                                                <label for="shipping_address-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                                <input type="text" name="shipping_address" id="shipping_address-{{ $item->id }}" placeholder="Enter shipping address" class="w-full p-2 border rounded">
                                            </div>
                                            <div class="mb-2">
                                                <label for="payment_method-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                                <select name="payment_method" id="payment_method-{{ $item->id }}" class="w-full p-2 border rounded">
                                                    <option value="card">Credit Card</option>
                                                    <option value="paypal">PayPal</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Purchase</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $merchandise->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection