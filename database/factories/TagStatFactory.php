<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\TagStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TagStat>
 */
class TagStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_id' => Tag::factory(),
            'usage_count' => fake()->numberBetween(0, 1000),
            'last_used_at' => fake()->optional()->dateTime(),
        ];
    }
}
