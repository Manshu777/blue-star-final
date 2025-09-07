<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function complete($orderId)
    {
        $order = Orders::with('items.product')->findOrFail($orderId);

        return view('orders.complete', compact('order'));
    }
}