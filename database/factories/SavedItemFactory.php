<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\SavedItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavedItem>
 */
class SavedItemFactory extends Factory
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
            'item_type' => Post::class,
            'item_id' => Post::factory(),
        ];
    }
}
