<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes with Sanctum authentication
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