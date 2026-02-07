<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\ResumeSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResumeSection>
 */
class ResumeSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resume_id' => Resume::factory(),
            'type' => fake()->randomElement(['education', 'experience', 'skill', 'certificate']),
            'content' => fake()->paragraph(),
        ];
    }
}
