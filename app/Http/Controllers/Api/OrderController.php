<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * For admin: all orders
     * For customer: user's orders only
     */
    public function index(Request $request)
    {
        $user = $request->user();

        \Log::info('📦 API: Fetching orders', [
            'user_id' => $user->id,
            'user_type' => $user->user_type
        ]);

        try {
            // Check if user is admin to determine scope
            $isAdmin = $user->user_type === 'admin';

            if ($isAdmin) {
                // Admin sees all orders with optional filtering
                $query = Order::with(['user', 'product']);
                
                // Optional status filter
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                $orders = $query->latest()->get(); // Changed from paginate to get
            } else {
                // Regular user sees only their own orders
                $orders = $user->orders()
                    ->with('product')
                    ->latest()
                    ->get(); // Changed from paginate to get
            }

            \Log::info('✅ API: Orders fetched successfully', [
                'user_id' => $user->id,
                'count' => $orders->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => $orders // Now returns a Collection (array-like), not a Paginator
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ API: Failed to fetch orders', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*** Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        $user = $request->user();
        
        \Log::info('🛒 API: Creating order', [
            'user_id' => $user->id,
            'cart_items' => $request->cart_items
        ]);
        
        try {
            // Validate the request
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'zip' => 'required|string|max:20',
                'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
                'cart_items' => 'required|array|min:1',
                'cart_items.*.product_id' => 'required|exists:products,id',
                'cart_items.*.quantity' => 'required|integer|min:1',
            ]);

            $cartItems = $validated['cart_items'];

            DB::beginTransaction();

            $orders = [];
            $totalAmount = 0;

            // Create an order for each cart item
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_id']}");
                }

                // Calculate total for this item
                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                // Create order record
                $order = Order::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $itemTotal,
                    'status' => Order::STATUS_PENDING,
                ]);

                $orders[] = $order;
            }

            DB::commit();

            \Log::info('✅ API: Order created successfully', [
                'user_id' => $user->id,
                'order_count' => count($orders),
                'total_amount' => $totalAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Orders created successfully',
                'data' => $orders,
                'total_amount' => $totalAmount,
                'customer_info' => [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'zip' => $validated['zip'],
                    'payment_method' => $validated['payment_method'],
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('⚠️ API: Order validation failed', [
                'user_id' => $user->id,
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('❌ API: Order creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 422);
        }
    }

    /*** Display the specified resource.*/
    public function show(string $id, Request $request)
    {
        $user = $request->user();
        
        \Log::info('🔍 API: Fetching order', [
            'user_id' => $user->id,
            'order_id' => $id
        ]);
        
        try {
            $order = Order::with(['user', 'product'])->findOrFail($id);

            // Authorization: Admin can access any order, regular user can only access their own
            if ($user->user_type !== 'admin' && $order->user_id !== $user->id) {
                \Log::warning('⚠️ API: Unauthorized order access attempt', [
                    'user_id' => $user->id,
                    'order_id' => $id,
                    'order_owner' => $order->user_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this order.'
                ], 403);
            }

            \Log::info('✅ API: Order fetched successfully', [
                'user_id' => $user->id,
                'order_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order retrieved successfully',
                'data' => $order
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('⚠️ API: Order not found', [
                'user_id' => $user->id,
                'order_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('❌ API: Failed to fetch order', [
                'user_id' => $user->id,
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * For admin: update order status
     * For customer: not allowed
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();
        
        // Only admins can update orders
        if ($user->user_type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update orders.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /*** Remove the specified resource from storage.*/
    public function destroy(string $id, Request $request)
    {
        $user = $request->user();
        
        // Only admins can delete orders
        if ($user->user_type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete orders.'
            ], 403);
        }

        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}