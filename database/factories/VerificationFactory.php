<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verification>
 */
class VerificationFactory extends Factory
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
            'type' => fake()->randomElement(['email', 'company', 'identity']),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'document_url' => fake()->optional()->url(),
        ];
    }
}
