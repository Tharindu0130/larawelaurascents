<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Product::create([
        'name' => 'Midnight Jasmine',
        'description' => 'A mysterious and enchanting fragrance...',
        'price' => 4500.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=400',
        'category' => 'Women'
    ]);

    \App\Models\Product::create([
        'name' => 'Ocean Breeze',
        'description' => 'Fresh and invigorating scent...',
        'price' => 3800.00,
        'stock' => 30,
        'image_url' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=400',
        'category' => 'Unisex'
    ]);
    // Repeat for your other products from the SQL file
}
}
