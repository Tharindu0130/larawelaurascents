<?php

use App\Models\User;
use App\Models\Product;

// Test API response for different user types
echo "=== Product API Test ===\n";

// Get a customer user
$customer = User::where('user_type', 'customer')->first();
echo "Customer user: " . ($customer ? $customer->email : 'None found') . "\n";

// Get an admin user  
$admin = User::where('user_type', 'admin')->first();
echo "Admin user: " . ($admin ? $admin->email : 'None found') . "\n";

// Count total products
$totalProducts = Product::count();
echo "Total products in database: $totalProducts\n";

// Test API response
echo "\n=== Testing API Responses ===\n";

// Simulate customer API call
if ($customer) {
    echo "Customer API call would return: $totalProducts products\n";
}

// Simulate admin API call  
if ($admin) {
    echo "Admin API call would return: $totalProducts products\n";
}

echo "\n=== Product Data Sample ===\n";
$sampleProduct = Product::first();
if ($sampleProduct) {
    echo "Sample product:\n";
    echo "- Name: " . $sampleProduct->name . "\n";
    echo "- Price: " . $sampleProduct->price . "\n";
    echo "- Stock: " . $sampleProduct->stock . "\n";
    echo "- User ID: " . $sampleProduct->user_id . "\n";
}

echo "\nTest completed.\n";