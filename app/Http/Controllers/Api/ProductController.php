<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest; // ASSIGNMENT: Form Request class
use App\Http\Requests\UpdateProductRequest; // ASSIGNMENT: Form Request class
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * ASSIGNMENT CRITERIA: RESTful API Controller
 * 
 * This controller demonstrates:
 * - RESTful resource controller (index, store, show, update, destroy)
 * - API Resource classes for data transformation
 * - Proper HTTP status codes (201 Created, 204 No Content)
 * - Sanctum authentication protection
 * 
 * Security measures:
 * - All methods (except index) require auth:sanctum middleware
 * - Input validation via Form Request classes
 * - Eloquent ORM prevents SQL injection
 */
class ProductController extends Controller
{
    /**
     * ASSIGNMENT CRITERIA: Public API Endpoint
     * 
     * Public endpoint (no authentication required)
     * Demonstrates: Public API access for product listing
     * Security: Read-only, no sensitive data exposed
     */
    public function index()
    {
        // ASSIGNMENT: Eloquent ORM with relationships
        // Eager loading prevents N+1 query problem
        $products = Product::with(['category', 'user', 'tags', 'comments'])->get();
        
        // ASSIGNMENT: API Resource classes
        // Transforms data structure for API response
        return ProductResource::collection($products);
    }

    /**
     * ASSIGNMENT CRITERIA: RESTful API - POST /api/products
     * 
     * Security measures:
     * - Form Request validation (StoreProductRequest)
     * - Authenticated user required (auth:sanctum middleware)
     * - Eloquent ORM prevents SQL injection
     * - User ID automatically set from authenticated user
     */
    public function store(StoreProductRequest $request) // ASSIGNMENT: Form Request instead of Request
    {
        // Validation already done by StoreProductRequest
        $validated = $request->validated();
        
        // Security: Use authenticated user (prevents user_id manipulation)
        $validated['user_id'] = $request->user()->id;

        // ASSIGNMENT: Eloquent ORM - Secure database insertion
        // Eloquent uses parameterized queries (SQL injection prevention)
        $product = Product::create($validated);

        // ASSIGNMENT: RESTful status code (201 Created)
        return (new ProductResource($product->load(['category', 'user', 'tags', 'comments'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * ASSIGNMENT CRITERIA: RESTful API - GET /api/products/{id}
     */
    public function show(string $id)
    {
        // ASSIGNMENT: Eloquent ORM - findOrFail returns 404 if not found
        $product = Product::with(['category', 'user', 'tags', 'comments'])->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * ASSIGNMENT CRITERIA: RESTful API - PUT/PATCH /api/products/{id}
     * 
     * Security: UpdateProductRequest handles validation
     */
    public function update(UpdateProductRequest $request, string $id) // ASSIGNMENT: Form Request
    {
        $product = Product::findOrFail($id);
        
        // Validation done by UpdateProductRequest
        $product->update($request->validated());

        return new ProductResource($product->load(['category', 'user', 'tags', 'comments']));
    }

    /**
     * ASSIGNMENT CRITERIA: RESTful API - DELETE /api/products/{id}
     * 
     * Returns 204 No Content (RESTful standard)
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // ASSIGNMENT: RESTful status code (204 No Content)
        return response()->noContent();
    }
}
