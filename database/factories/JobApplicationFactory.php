<?php

namespace Database\Factories;

use App\Models\CareerJob;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => CareerJob::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
