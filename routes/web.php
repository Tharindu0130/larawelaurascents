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

// Debug route (remove after testing)
Route::get('/debug-session', function () {
    return view('debug-session');
})->middleware('auth');

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
| Authentication (separate admin / user login per assignment)
|--------------------------------------------------------------------------
*/

// User (customer) login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Admin login (separate route & page, same UI)
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])
    ->name('admin.login.form');
Route::post('/admin/login', [LoginController::class, 'loginAdmin'])
    ->name('admin.login');

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

Route::post('/cart/add/{productId}', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])
    ->name('cart.remove');

Route::post('/cart/update/{productId}', [CartController::class, 'updateQuantity'])
    ->name('cart.update');


/*
|--------------------------------------------------------------------------
| Checkout & Orders
|--------------------------------------------------------------------------
*/

// Checkout page (public, but should be accessed after cart)
Route::get('/checkout', function () {
    return view('customer.checkout');
})->name('checkout');

Route::get('/order-confirmation', function () {
    return view('customer.order-confirmation');
})->name('order.confirmation');

Route::get('/test-api', function () {
    return view('test-api');
})->name('test.api');

// Place order form submission is now handled via API
// The actual order creation happens through the API endpoint

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Login)
|--------------------------------------------------------------------------
| Uses 'auth' middleware for session-based authentication (web routes)
| Sanctum tokens stored in session allow Livewire/frontend to call APIs
*/

Route::middleware([
    'auth',  // Session-based auth for web routes (NOT auth:sanctum)
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    // Admin Products Management
    Route::get('/admin/products', \App\Livewire\AdminProducts::class)
        ->middleware('role:admin')
        ->name('admin.products');

    // Admin Customers Management
    Route::get('/admin/customers', \App\Livewire\AdminCustomers::class)
        ->middleware('role:admin')
        ->name('admin.customers');

    // Admin Orders Management
    Route::get('/admin/orders', \App\Livewire\AdminOrders::class)
        ->middleware('role:admin')
        ->name('admin.orders');


    // Customer Dashboard
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->middleware('role:customer')->name('customer.dashboard');

    // Customer Orders (protected - customer only)
    Route::middleware('role:customer')->group(function () {
        Route::get('/orders', [App\Http\Controllers\OrderController::class, 'myOrders'])
            ->name('orders.index');
        
        Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])
            ->name('orders.show');
    });

});
