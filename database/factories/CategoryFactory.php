<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $name = $this->faker->department;
        $slug = Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 1000); // Ensure uniqueness

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->faker->sentence(15),
            'imge' => $this->faker->imageUrl,
        ];
    }
}
