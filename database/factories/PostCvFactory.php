<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostCv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostCv>
 */
class PostCvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'cv_file' => fake()->word() . '.pdf',
            'original_filename' => fake()->word() . '.pdf',
            'message' => fake()->optional()->sentence(),
        ];
    }
}
