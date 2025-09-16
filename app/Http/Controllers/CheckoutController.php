<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();
        $total = $cart->total;

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required',
            'billing_email' => 'required|email',
            'billing_address' => 'required',
        ]);

        $user = Auth::user();
        $cart = $this->getCart();

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $cart->total,
            'billing_address' => [
                'name' => $request->billing_name,
                'email' => $request->billing_email,
                'address' => $request->billing_address,
                // etc.
            ],
            'shipping_address' => $request->shipping_same ? null : [/* ... */],
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Payment processing (Stripe example)
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = Charge::create([
            'amount' => $order->total * 100,
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => 'Order #' . $order->id,
        ]);

        // Create payment record
        $order->payment()->create([
            'transaction_id' => $charge->id,
            'amount' => $order->total,
            'status' => 'paid',
        ]);

        $order->status = 'completed';
        $order->save();

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('orders.complete', $order->id);
    }

    private function getCart()
    {
        
        $user = Auth::user();
        if ($user) {
            return Cart::where('user_id', $user->id)->firstOrFail();
        } else {
            $sessionId = session()->getId();
            return Cart::where('session_id', $sessionId)->firstOrFail();
        }
    }
}