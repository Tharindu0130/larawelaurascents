<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MobileProductController extends Controller
{
    /**
     * List all products for mobile
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'user'])
            ->latest()
            ->paginate($request->get('limit', 10));

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]
        ], 200);
    }

    /**
     * Show single product details
     */
    public function show($id)
    {
        $product = Product::with(['category', 'user', 'comments.user'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products->items()
        ], 200);
    }
}
