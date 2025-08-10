<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\MerchandiseOrder;
use Illuminate\Http\Request;

class MerchandiseController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_type' => 'required|string',
            'design_preview_url' => 'nullable|url'
        ]);
        $order = MerchandiseOrder::create($validated + ['status' => 'pending']);
        return response()->json($order, 201);
    }
}