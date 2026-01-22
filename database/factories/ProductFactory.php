<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $perfumeNames = [
            'Midnight Rose', 'Ocean Breeze', 'Vanilla Dreams', 'Citrus Burst', 'Lavender Fields',
            'Jasmine Nights', 'Sandalwood Serenity', 'Amber Glow', 'Musk Mystique', 'Floral Essence',
            'Eucalyptus Fresh', 'Cedarwood Classic', 'Bergamot Bliss', 'Patchouli Passion', 'Ylang Ylang'
        ];

        return [
            'name' => fake()->randomElement($perfumeNames),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 20, 500),
            'stock' => fake()->numberBetween(0, 100),
            'image' => 'https://picsum.photos/400/400?random=' . fake()->numberBetween(1, 1000),
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}
