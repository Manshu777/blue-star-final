<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'product' => 'required|string',
            // Add more: photo_id, etc.
        ]);

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // Example: Create checkout session
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $request->product,
                    ],
                    'unit_amount' => 500, // Example $5.00
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }
}