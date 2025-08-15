<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Merchandise;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display the store index page
     */
    public function index()
    {
        $featuredPhotos = Photo::where('is_featured', true)->take(6)->get();
        $featuredMerchandise = Merchandise::where('is_featured', true)->take(6)->get();
        
        return view('store.index', compact('featuredPhotos', 'featuredMerchandise'));
    }

    /**
     * Display merchandise catalog
     */
    public function merchandise()
    {
        $merchandise = Merchandise::paginate(12);
        
        return view('store.merchandise', compact('merchandise'));
    }

    /**
     * Process photo purchase
     */
    public function purchasePhoto(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);
        
        $request->validate([
            'license_type' => 'required|in:personal,commercial',
            'payment_method' => 'required|in:card,paypal'
        ]);

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'item_type' => 'photo',
                'item_id' => $photo->id,
                'amount' => $photo->price,
                'license_type' => $request->license_type,
                'payment_method' => $request->payment_method,
                'status' => 'pending'
            ]);

            $this->processPayment($order, $request->payment_method);

            return redirect()->route('store.orders')
                ->with('success', 'Photo purchased successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }

    /**
     * Process merchandise purchase
     */
    public function purchaseMerchandise(Request $request)
    {
        $request->validate([
            'merchandise_id' => 'required|exists:merchandise,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:card,paypal',
            'shipping_address' => 'required|string|max:255'
        ]);

        $merchandise = Merchandise::findOrFail($request->merchandise_id);

        if ($merchandise->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'item_type' => 'merchandise',
                'item_id' => $merchandise->id,
                'amount' => $merchandise->price * $request->quantity,
                'quantity' => $request->quantity,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'status' => 'pending'
            ]);

            $this->processPayment($order, $request->payment_method);

            $merchandise->decrement('stock', $request->quantity);

            return redirect()->route('store.orders')
                ->with('success', 'Merchandise purchased successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }

    /**
     * Display user's orders
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['photo', 'merchandise'])
            ->latest()
            ->paginate(10);

        return view('store.orders', compact('orders'));
    }

    /**
     * Process payment (placeholder for actual payment gateway integration)
     */
    private function processPayment(Order $order, string $paymentMethod)
    {
        // Implement payment gateway integration (e.g., Stripe, PayPal)
        // This is a placeholder
        $order->update(['status' => 'completed']);
    }
}