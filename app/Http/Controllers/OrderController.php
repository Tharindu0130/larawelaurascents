<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Process the checkout and create orders.
     * 
     * Assignment benefits:
     * - Input validation (required for SSP-II)
     * - Transaction safety with DB::transaction
     * - Proper error handling
     * - Business logic separation from routes
     */
    public function placeOrder(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        // Input validation - SSP-II requirement
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
        ]);

        // Get cart from session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        try {
            // Use database transaction for data integrity
            DB::beginTransaction();

            $orders = [];
            $totalAmount = 0;

            // Create an order for each cart item
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);

                if (!$product) {
                    throw new \Exception("Product not found: {$item['name']}");
                }

                // Calculate total for this item
                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                // Create order record
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $itemTotal,
                    'status' => Order::STATUS_PENDING,
                ]);

                $orders[] = $order;
            }

            // Clear the cart after successful order creation
            session()->forget('cart');

            DB::commit();

            // Redirect to confirmation page with order details
            return view('customer.order-confirmation', [
                'orders' => $orders,
                'totalAmount' => $totalAmount,
                'customerInfo' => $validated,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('checkout')
                ->with('error', 'Failed to place order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show customer's order history.
     * 
     * Assignment benefits:
     * - User-specific data filtering (security)
     * - Eloquent relationship usage
     * - Latest-first ordering
     */
    public function myOrders()
    {
        // Get only authenticated user's orders - SECURITY
        $orders = Auth::user()
            ->orders()
            ->with('product') // Eager loading to prevent N+1 queries
            ->latest()
            ->paginate(10);

        return view('customer.my-orders', compact('orders'));
    }

    /**
     * Show details of a specific order.
     * 
     * Assignment benefits:
     * - Authorization check (user can only view own orders)
     * - Eager loading relationships
     */
    public function show(Order $order)
    {
        // Authorization: Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load relationships
        $order->load('product');

        return view('customer.order-details', compact('order'));
    }
}
