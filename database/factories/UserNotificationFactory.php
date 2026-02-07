<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotification>
 */
class UserNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'source_user_id' => User::factory(),
            'type' => fake()->randomElement(['like', 'comment', 'follow', 'mention']),
            'post_id' => Post::factory(),
            'message' => fake()->sentence(),
            'is_read' => false,
        ];
    }
}
