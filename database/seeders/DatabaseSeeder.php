<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Creates a single admin account when missing. Configure via ADMIN_* env vars.
     */
    public function run(): void
    {
        $email = strtolower(trim((string) env('ADMIN_EMAIL', 'admin@example.com')));
        $password = env('ADMIN_PASSWORD');

        if (! is_string($password) || $password === '') {
            $password = 'password';
            $this->command?->warn('ADMIN_PASSWORD is not set; using default "password". Set ADMIN_PASSWORD in .env for production.');
        }

        $user = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'username' => env('ADMIN_USERNAME', 'admin'),
                'role' => 'admin',
                'is_admin' => true,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'profile_photo_path' => null,
                'current_team_id' => null,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]
        );

        $user->forceFill(['password_set_at' => now()])->save();
    }
}
