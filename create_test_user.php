<?php

/*
 * Quick script to create a test user for login testing
 * 
 * Usage: php create_test_user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "Create Test User for Laravel API\n";
echo "========================================\n\n";

// Check if test user already exists
$existingUser = User::where('email', 'test@example.com')->first();

if ($existingUser) {
    echo "✅ Test user already exists:\n";
    echo "   Email: test@example.com\n";
    echo "   Password: password\n";
    echo "   Name: {$existingUser->name}\n";
    echo "   ID: {$existingUser->id}\n\n";
    
    echo "Do you want to reset the password? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    
    if (trim($line) == 'y' || trim($line) == 'Y') {
        $existingUser->password = Hash::make('password');
        $existingUser->save();
        echo "✅ Password reset to: password\n\n";
    }
} else {
    // Create new test user
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'user_type' => 'customer',
        'email_verified_at' => now(),
    ]);

    echo "✅ Test user created successfully!\n\n";
    echo "   Email: test@example.com\n";
    echo "   Password: password\n";
    echo "   Name: {$user->name}\n";
    echo "   ID: {$user->id}\n\n";
}

// List all users
echo "========================================\n";
echo "All Users in Database:\n";
echo "========================================\n";

$allUsers = User::all();

if ($allUsers->count() > 0) {
    foreach ($allUsers as $user) {
        echo "  • ID: {$user->id} | Email: {$user->email} | Name: {$user->name}\n";
    }
} else {
    echo "  No users found in database.\n";
}

echo "\n========================================\n";
echo "You can now login with:\n";
echo "  Email: test@example.com\n";
echo "  Password: password\n";
echo "========================================\n";
