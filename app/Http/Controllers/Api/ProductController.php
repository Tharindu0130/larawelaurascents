<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest; 
use App\Http\Requests\UpdateProductRequest; 
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    
    public function index()
    {
        \Log::info('📋 API: Fetching all products');
        
        try {
            // ASSIGNMENT: Eloquent ORM with relationships
            $products = Product::with(['category', 'user', 'tags', 'comments'])->get();
            
            \Log::info('✅ API: Products fetched successfully', [
                'count' => $products->count()
            ]);
            
            // Transforms data structure for API response
            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => ProductResource::collection($products)
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ API: Failed to fetch products', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /** Search products by name or description* Public endpoint for mobile app search functionality*/
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        
        \Log::info('🔍 API: Search products', ['query' => $query]);
        
        try {
            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No search query provided',
                    'data' => []
                ]);
            }

            $products = Product::with(['category', 'user', 'tags', 'comments'])
                ->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->get();

            \Log::info('✅ API: Search completed', [
                'query' => $query,
                'results' => $products->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => ProductResource::collection($products)
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ API: Search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(StoreProductRequest $request) 
    {
       
        $validated = $request->validated();
        
       
        $validated['user_id'] = $request->user()->id;

       
        $product = Product::create($validated);

        return (new ProductResource($product->load(['category', 'user', 'tags', 'comments'])))
            ->response()
            ->setStatusCode(201);
    }

    
    public function show(string $id)
    {
        \Log::info('🔍 API: Fetching product', ['product_id' => $id]);
        
        try {
            // ASSIGNMENT: Eloquent ORM - findOrFail returns 404 if not found
            $product = Product::with(['category', 'user', 'tags', 'comments'])->findOrFail($id);
            
            \Log::info('✅ API: Product fetched successfully', [
                'product_id' => $id,
                'product_name' => $product->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => new ProductResource($product)
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('⚠️ API: Product not found', ['product_id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('❌ API: Failed to fetch product', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function update(UpdateProductRequest $request, string $id) // ASSIGNMENT: Form Request
    {
        $product = Product::findOrFail($id);
        
        // Validation done by UpdateProductRequest
        $product->update($request->validated());

        return new ProductResource($product->load(['category', 'user', 'tags', 'comments']));
    }

  
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // ASSIGNMENT: RESTful status code (204 No Content)
        return response()->noContent();
    }
}
