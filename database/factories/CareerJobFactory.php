<?php

namespace Database\Factories;

use App\Models\CareerJob;
use App\Models\Company;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerJob>
 */
class CareerJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialty = Specialty::factory()->create();
        $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);
        
        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'specialty_id' => $specialty->id,
            'sub_specialty_id' => $subSpecialty->id,
            'location' => fake()->city(),
            'job_type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'freelance', 'internship', 'remote']),
        ];
    }
}
