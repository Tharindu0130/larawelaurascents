<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;
<<<<<<< HEAD
use App\Http\Controllers\CartController;

// Temporary test route
Route::get('/test', function () {
    return 'Test route working!';
})->name('test');

/*
|--------------------------------------------------------------------------
| Public Customer Pages
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('customer.home');
})->name('home');

Route::get('/products', function () {
    $products = \App\Models\Product::all();
    return view('customer.products', compact('products'));
})->name('products');

Route::get('/about', function () {
    return view('customer.about');
})->name('about');

Route::get('/contact', function () {
    return view('customer.contact');
})->name('contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Cart Routes (NEW)
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{productId}', [CartController::class, 'updateQuantity'])->name('cart.update');

/*
|--------------------------------------------------------------------------
| Customer Portal (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/checkout', function () {
        return view('customer.checkout');
    })->name('checkout');
});

/*
|--------------------------------------------------------------------------
| Admin Panel (Admin Only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('admin.products');

    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('admin.categories');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('admin.orders');
});
=======
use Livewire\Volt\Volt;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Admin Portal (protected by admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin-dashboard');
    })->name('admin.dashboard');
});

// Customer Portal (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer-dashboard');
    })->name('customer.dashboard');
});
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
