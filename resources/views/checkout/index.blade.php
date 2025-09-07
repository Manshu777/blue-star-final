@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Billing Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Billing Information</h2>
                <div class="mb-4">
                    <label for="billing_name" class="block mb-1">Name</label>
                    <input type="text" id="billing_name" name="billing_name" class="w-full border rounded px-4 py-2">
                </div>
                <div class="mb-4">
                    <label for="billing_email" class="block mb-1">Email</label>
                    <input type="email" id="billing_email" name="billing_email" class="w-full border rounded px-4 py-2">
                </div>
                <div class="mb-4">
                    <label for="billing_address" class="block mb-1">Address</label>
                    <textarea id="billing_address" name="billing_address" class="w-full border rounded px-4 py-2"></textarea>
                </div>
                <!-- More fields: phone, city, state, zip -->
            </div>

            <!-- Order Summary -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                @foreach ($cartItems as $item)
                    <div class="flex justify-between mb-2">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <hr class="my-4">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Payment Information</h2>
            <!-- Stripe Elements or PayPal buttons -->
            <div id="card-element" class="border rounded px-4 py-2 mb-4"></div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600 w-full">Pay Now</button>
        </div>
    </form>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');
        // Handle form submit for token
    </script>
</div>
@endsection