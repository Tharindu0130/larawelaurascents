<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;

// ============================================
// PUBLIC API ROUTES (No Authentication Required)
// ============================================

// API Login (returns JSON token) – no auth required
Route::post('/login', [AuthController::class, 'login']);

// Public Products Listing (NO Sanctum auth required - accessible to everyone)
Route::get('/products', [ProductController::class, 'index']);

// Cart routes (require Sanctum Bearer token + web middleware for session support)
Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{productId}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
});

// ============================================
// PROTECTED WEB API ROUTES (Authentication Required)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Main API Resources
    Route::apiResource('products', ProductController::class)->except(['index']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('orders', OrderController::class);
});

// 📱 MOBILE API ROUTES (Dedicated for Flutter)
// 📱 MOBILE API ROUTES (Dedicated for Flutter)
Route::prefix('mobile')->group(function () {
    // ============================================
    // PUBLIC ROUTES (No Authentication Required)
    // ============================================
    
    // Authentication
    Route::post('/register', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'login']);
    
    // Products (public - anyone can browse)
    Route::get('/products', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'index']);
    Route::get('/products/search', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'search']);
    Route::get('/products/{id}', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'show']);
    
    // Categories (public)
    Route::get('/categories', [App\Http\Controllers\Api\Mobile\MobileCategoryController::class, 'index']);
    
    // ============================================
    // PROTECTED ROUTES (Authentication Required)
    // ============================================
    
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'logout']);
        Route::get('/profile', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'profile']);
        Route::put('/profile/update', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'updateProfile']);
        
        // Cart
        Route::get('/cart', [App\Http\Controllers\Api\Mobile\MobileCartController::class, 'index']);
        Route::post('/cart', [App\Http\Controllers\Api\Mobile\MobileCartController::class, 'store']);
        Route::put('/cart/{id}', [App\Http\Controllers\Api\Mobile\MobileCartController::class, 'update']);
        Route::delete('/cart/{id}', [App\Http\Controllers\Api\Mobile\MobileCartController::class, 'destroy']);
        
        // Orders
        Route::get('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'index']);
        Route::post('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'store']);
        Route::get('/orders/{id}', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'show']);
        
        // Wishlist
        Route::get('/wishlist', [App\Http\Controllers\Api\Mobile\MobileWishlistController::class, 'index']);
        Route::post('/wishlist', [App\Http\Controllers\Api\Mobile\MobileWishlistController::class, 'store']);
        Route::delete('/wishlist/{id}', [App\Http\Controllers\Api\Mobile\MobileWishlistController::class, 'destroy']);
    });
});