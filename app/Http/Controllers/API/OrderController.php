<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class OrderController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:stripe,razorpay,paypal'
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Photo Purchase'],
                    'unit_amount' => $validated['total_amount'] * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/success',
            'cancel_url' => env('APP_URL') . '/cancel',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'total_amount' => $validated['total_amount'],
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        return response()->json(['order' => $order, 'checkout_url' => $session->url], 201);
    }
}