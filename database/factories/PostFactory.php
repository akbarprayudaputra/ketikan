<?php

namespace Database\Factories;

use App\Models\User;
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
            'title' => $this->faker->sentence, // kalau ada kolom title
            'body' => $this->faker->paragraph, // kalau ada kolom body
            'user_id' => User::factory(),      // 👈 ini wajib biar selalu ada user
        ];
    }
}
