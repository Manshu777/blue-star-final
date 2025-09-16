@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-4xl font-bold mb-8 text-center text-gray-800">🛒 Your Shopping Cart</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="text-center py-20 bg-white shadow rounded-lg">
                <p class="text-lg text-gray-600">Your cart is empty.</p>
                <a href="{{ url('shop') }}"
                    class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                    Continue Shopping
                </a>
            </div>
        @else
            <!-- Cart Items -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <table class="w-full text-sm md:text-base">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Product</th>
                            <th class="px-6 py-3 text-left">Price</th>
                            <th class="px-6 py-3 text-left">Qty</th>
                            <th class="px-6 py-3 text-left">Subtotal</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                            class="w-16 h-16 object-cover rounded-lg shadow mr-4">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                            @if ($item->options)
                                                <p class="text-sm text-gray-500">Options:
                                                    {{ implode(', ', json_decode($item->options, true)) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="w-16 border-gray-300 rounded-lg shadow-sm px-2 py-1 focus:ring focus:ring-blue-200">
                                        <button type="submit"
                                            class="text-blue-600 hover:text-blue-800 font-semibold">Update</button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    ${{ number_format($item->price * $item->quantity, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary -->
            <div class="flex flex-col md:flex-row md:justify-between gap-8">
                <!-- Coupon -->
                <form action="{{ route('cart.applyCoupon') }}" method="POST"
                    class="flex items-center bg-white shadow rounded-lg px-6 py-4 space-x-3 w-full md:w-1/2">
                    @csrf
                    <input type="text" name="coupon" placeholder="Enter coupon code"
                        class="flex-grow border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring focus:ring-blue-200">
                    <button type="submit"
                        class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow hover:bg-gray-800 transition">
                        Apply
                    </button>
                </form>

                <!-- Summary -->
                <div class="bg-white shadow rounded-lg p-6 w-full md:w-1/2">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h3>
                    <div class="space-y-2 text-gray-700">
                        <p class="flex justify-between"><span>Subtotal:</span> <span>${{ number_format($subtotal, 2) }}</span>
                        </p>
                        <p class="flex justify-between"><span>Tax (10%):</span> <span>${{ number_format($tax, 2) }}</span></p>
                        <p class="flex justify-between"><span>Shipping:</span> <span
                                class="text-green-600 font-medium">Free</span></p>
                    </div>
                    <hr class="my-4">
                    <p class="flex justify-between text-xl font-bold text-gray-900">
                        <span>Total:</span> <span>${{ number_format($total, 2) }}</span>
                    </p>
                    <a href="{{ route('checkout.index') }}"
                        class="mt-6 block bg-blue-600 text-center text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                        Proceed to Checkout →
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection