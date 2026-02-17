<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Linus Torvalds',
            'email' => 'linus@example.com',
            'username' => 'linus',
            'role' => 'admin',
            'is_admin' => true,
            'password' => Hash::make('Linus2003$'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 10 users, each with 10 posts
        User::factory(10)
            ->has(Post::factory()->count(10))
            ->create();
    }
}
