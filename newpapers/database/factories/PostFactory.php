<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->paragraph,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'published_at' => $this->faker->dateTime,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
