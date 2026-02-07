<?php

namespace Database\Factories;

use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubSpecialty>
 */
class SubSpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'specialty_id' => Specialty::factory(),
            'name' => fake()->word(),
        ];
    }
}
