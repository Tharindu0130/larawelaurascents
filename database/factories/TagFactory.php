<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
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
            'slug' => fake()->unique()->slug(),
=======
        $tagNames = ['Luxury', 'Elegant', 'Fresh', 'Bold', 'Subtle', 'Romantic', 'Modern', 'Classic'];
        $name = fake()->unique()->randomElement($tagNames);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
        ];
    }
}
