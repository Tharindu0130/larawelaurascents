<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // Create 1 regular user to own seeded products
        $owner = User::factory()->create([
            'name' => 'Seed Owner',
            'email' => 'owner@aurascent.local',
            'password' => Hash::make('password'),
            'user_type' => 'customer',
=======
        // Create 1 admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@aurascents.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
        ]);

        // Create 5 categories
        $categories = Category::factory(5)->create();

        // Create some tags
        $tags = Tag::factory(8)->create();

        // Create 10 perfumes (products)
        $products = Product::factory(10)->create([
<<<<<<< HEAD
            'user_id' => $owner->id,
=======
            'user_id' => $admin->id,
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
        ]);

        // Assign random categories to products
        foreach ($products as $product) {
            $product->update([
                'category_id' => $categories->random()->id,
            ]);

            // Attach random tags to products
            $product->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }

        // Create some comments
        Comment::factory(20)->create();
    }
}
