<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;
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
