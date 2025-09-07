@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 text-center">
    <h1 class="text-3xl font-bold mb-6">Order Complete!</h1>
    <p class="text-lg mb-8">Thank you for your purchase. Your order #{{ $order->id }} has been placed successfully.</p>

    <!-- Order Details -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        @foreach ($order->items as $item)
            <div class="flex justify-between mb-2">
                <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
        @endforeach
        <hr class="my-4">
        <div class="flex justify-between text-xl font-bold">
            <span>Total</span>
            <span>${{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <!-- Download Links for Digital Products -->
    @if ($order->items->contains(function ($item) { return in_array($item->product->type, ['photo', 'video']); }))
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Downloads</h2>
            @foreach ($order->items as $item)
                @if (in_array($item->product->type, ['photo', 'video']))
                    <a href="{{ route('downloads.product', $item->product->id) }}" class="block text-blue-500 hover:underline mb-2">Download {{ $item->product->name }}</a>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Shipping Info for Physical -->
    @if ($order->items->contains(function ($item) { return $item->product->type === 'merchandise'; }))
        <div>
            <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
            <p>Your items will be shipped to: {{ $order->shipping_address['address'] ?? $order->billing_address['address'] }}</p>
            <!-- Integrate with print-on-demand for tracking -->
        </div>
    @endif

    <a href="{{ route('shop.index') }}" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">Continue Shopping</a>
</div>
@endsection