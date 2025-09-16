<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = $subtotal * 0.1; // 10% tax example
        $total = $subtotal + $tax;

        return view('cart.cart', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        $couponCode = $request->input('coupon');

        // simple demo logic
        if ($couponCode === 'DISCOUNT10') {
            return redirect()->route('cart.index')->with('success', 'Coupon applied! 10% discount.');
        }

        return redirect()->route('cart.index')->with('success', 'Invalid coupon code.');
    }

    

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity += $request->quantity ?? 1;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->price,
            ]);
        }

        $this->updateCartTotal($cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->quantity = $request->quantity;
        $item->save();

        $this->updateCartTotal($item->cart);

        return redirect()->route('cart.index');
    }

    public function remove($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $cart = $item->cart;
        $item->delete();

        $this->updateCartTotal($cart);

        return redirect()->route('cart.index');
    }

    private function getCart()
    {
        $user = Auth::user();
        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        } else {
            $sessionId = session()->getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    private function updateCartTotal($cart)
    {
        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->total = $total;
        $cart->save();
    }
}