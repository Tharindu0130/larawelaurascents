<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;

// 📱 MOBILE API ROUTES (Dedicated for Flutter)
Route::prefix('mobile')->group(function () {
    
    // Auth
    Route::post('/login', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'login']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'logout']);
        
        // Products
        Route::get('/products', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'index']);
        Route::get('/products/search', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'search']);
        Route::get('/products/{id}', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'show']);
        
        // Orders
        Route::get('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'index']);
        Route::post('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'store']);
        Route::get('/orders/{id}', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'show']);
    });
});

// Legacy/Placeholder routes (keep for backward compatibility if needed)
Route::middleware('auth:sanctum')->group(function () {

    // User info
Route::get('/user', function (Request $request) {
    return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // API Resources
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('comments', CommentController::class);
});