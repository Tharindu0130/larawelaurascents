<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MobileOrderController extends Controller
{
    /**
     * Get user order history
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }

    /**
     * Place a new order from mobile
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->product_id);
        $itemTotal = $product->price * $request->quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $itemTotal,
            'status' => Order::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => $order->load('product')
        ], 201);
    }

    /**
     * Get single order details
     */
    public function show($id)
    {
        $order = Order::with('product')->find($id);

        if (!$order || $order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ], 200);
    }
}
