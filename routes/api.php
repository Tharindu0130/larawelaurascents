<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;

// API Login (returns JSON token) – no auth required
Route::post('/login', [AuthController::class, 'login']);

// Public read: products index (for customer products page, no DB access)
Route::get('/products', [ProductController::class, 'index']);

// 📱 MOBILE API ROUTES (Dedicated for Flutter)
Route::prefix('mobile')->group(function () {
    Route::post('/login', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Api\Mobile\MobileAuthController::class, 'logout']);
        Route::get('/products', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'index']);
        Route::get('/products/search', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'search']);
        Route::get('/products/{id}', [App\Http\Controllers\Api\Mobile\MobileProductController::class, 'show']);
        Route::get('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'index']);
        Route::post('/orders', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'store']);
        Route::get('/orders/{id}', [App\Http\Controllers\Api\Mobile\MobileOrderController::class, 'show']);
    });
});

// Protected API (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class)->except(['index']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('posts', PostController::class);
});