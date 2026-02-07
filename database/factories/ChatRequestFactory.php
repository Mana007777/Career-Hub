<?php

namespace Database\Factories;

use App\Models\ChatRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRequest>
 */
class ChatRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'message_id' => Message::factory(),
            'status' => 'pending',
            'responded_at' => null,
        ];
    }
}
