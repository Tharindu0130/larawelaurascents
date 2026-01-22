<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(10),
            'slug' => fake()->unique()->slug(),
=======
        $categoryNames = ['Floral', 'Woody', 'Citrus', 'Oriental', 'Fresh'];
        $name = fake()->unique()->randomElement($categoryNames);

        return [
            'name' => $name,
            'description' => fake()->sentence(10),
            'slug' => \Illuminate\Support\Str::slug($name),
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
        ];
    }
}
