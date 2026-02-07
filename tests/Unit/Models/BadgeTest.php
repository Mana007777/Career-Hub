<?php

use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('badge can be created', function () {
    $badge = Badge::factory()->create([
        'name' => 'Early Adopter',
        'icon' => 'star',
    ]);

    expect($badge->name)->toBe('Early Adopter')
        ->and($badge->icon)->toBe('star');
});

test('badge belongs to many users', function () {
    $badge = Badge::factory()->create();
    $user = User::factory()->create();
    $badge->users()->attach($user->id, ['earned_at' => now()]);

    expect($badge->users)->toHaveCount(1);
});
