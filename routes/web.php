<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LogoutController;

/*
|--------------------------------------------------------------------------
| Public Customer Pages
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('customer.home');
})->name('home');

// Products (USES CONTROLLER – IMPORTANT)
Route::get('/products', [ProductController::class, 'index'])
    ->name('products');

// About
Route::get('/about', function () {
    return view('customer.about');
})->name('about');

// Contact
Route::get('/contact', function () {
    return view('customer.contact');
})->name('contact');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'show'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store']);


/*
|--------------------------------------------------------------------------
| Cart Routes (SESSION BASED – WORKING)
|--------------------------------------------------------------------------
*/

// Show cart
Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.show');

Route::post('/cart/add/{product}', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])
    ->name('cart.remove');

Route::post('/cart/update/{productId}', [CartController::class, 'updateQuantity'])
    ->name('cart.update');



/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::get('/checkout', function () {
    return view('customer.checkout');
})->name('checkout');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    // Customer Dashboard
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->middleware('role:customer')->name('customer.dashboard');

});
