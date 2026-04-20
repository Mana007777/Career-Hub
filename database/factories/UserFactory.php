<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username' => self::makeFactoryUsername(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'password_set_at' => now(),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'role' => 'seeker',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * User created or linked via GitHub OAuth (no known password).
     */
    public function withGithub(): static
    {
        return $this->state(fn (array $attributes) => [
            'github_id' => fake()->unique()->numberBetween(100_000, 999_999_999),
            'password_set_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }

    /**
     * Usernames must match app validation /^[a-z0-9_]+$/i (Faker userName() often includes dots).
     */
    private static function makeFactoryUsername(): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9_]+/', '_', fake()->unique()->userName()) ?? '');
        $base = trim($base, '_') ?: 'user';

        return $base.'_'.fake()->unique()->numerify('####');
    }
}
