<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $count = collect($cart)->sum('quantity');
        
        \Log::info('Cart API index called', [
            'session_id' => session()->getId(),
            'cart_count' => count($cart),
            'cart_items' => array_keys($cart),
        ]);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'count' => $count,
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        $productId = $validated['product_id'];
        $quantity = $validated['quantity'] ?? 1;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $validated['name'],
                'price' => (float) $validated['price'],
                'quantity' => $quantity,
                'image' => $validated['image'] ?? null,
            ];
        }

        // Save to session and persist
        session()->put('cart', $cart);
        session()->save(); // Force save session
        
        // Verify it was saved
        $savedCart = session()->get('cart', []);
        
        $count = collect($cart)->sum('quantity');
        
        \Log::info('Cart add completed', [
            'session_id' => session()->getId(),
            'product_id' => $productId,
            'cart_count' => count($savedCart),
            'total_quantity' => $count,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart' => $savedCart, // Return what's actually in session
            'count' => $count,
            'total' => $this->calculateCartTotal($cart),
        ]);
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        $count = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cart' => $cart,
            'count' => $count,
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function remove(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $count = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart' => $cart,
            'count' => $count,
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }

    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}