<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserReputation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserReputation>
 */
class UserReputationFactory extends Factory
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
            'score' => fake()->numberBetween(0, 1000),
            'level' => fake()->randomElement(['junior', 'intermediate', 'senior', 'expert']),
        ];
    }
}
