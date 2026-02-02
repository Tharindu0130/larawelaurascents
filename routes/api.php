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
use App\Http\Controllers\Api\UserController;


// PUBLIC API ROUTES (No Authentication Required)


// Health check endpoint for Flutter connectivity testing
Route::get('/ping', function () {
    return response()->json(['message' => 'pong', 'status' => 'ok']);
});

// Authentication endpoints (no auth required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Products Listing (NO Sanctum auth required - accessible to everyone)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Cart routes (require Sanctum Bearer token + web middleware for session support)
Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{productId}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
});


// PROTECTED WEB API ROUTES (Authentication Required)

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Main API Resources
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('users', UserController::class);
});

