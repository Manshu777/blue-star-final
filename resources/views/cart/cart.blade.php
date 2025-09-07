@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Shopping Cart</h1>
    
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    
    @if ($cartItems->isEmpty())
        <p class="text-center text-gray-600">Your cart is empty. <a href="{{ route('shop.index') }}" class="text-blue-500 hover:underline">Continue shopping</a></p>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Subtotal</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                    <div>
                                        <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->options ? 'Options: ' . implode(', ', json_decode($item->options, true)) : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2">${{ number_format($item->price, 2) }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded px-2 py-1 mr-2">
                                    <button type="submit" class="text-blue-500 hover:underline">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-2">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Cart Summary -->
        <div class="flex justify-end mb-8">
            <div class="bg-gray-100 p-6 rounded-lg shadow-md w-full md:w-1/3">
                <h3 class="text-lg font-semibold mb-4">Cart Summary</h3>
                <p class="flex justify-between mb-2"><span>Subtotal:</span> <span>${{ number_format($subtotal, 2) }}</span></p>
                <p class="flex justify-between mb-2"><span>Tax (10%):</span> <span>${{ number_format($tax, 2) }}</span></p>
                <p class="flex justify-between mb-2"><span>Shipping:</span> <span>Free</span></p>
                <p class="flex justify-between text-xl font-bold"><span>Total:</span> <span>${{ number_format($total, 2) }}</span></p>
            </div>
        </div>
        
        <!-- Coupon and Checkout -->
        <div class="flex justify-between">
            <form action="{{ route('cart.applyCoupon') }}" method="POST" class="flex">
                @csrf
                <input type="text" name="coupon" placeholder="Coupon code" class="border rounded px-4 py-2 mr-2">
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Apply</button>
            </form>
            <a href="{{ route('checkout.index') }}" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">Proceed to Checkout</a>
        </div>
    @endif
</div>
@endsection