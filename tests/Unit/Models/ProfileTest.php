<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile can be created', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'bio' => 'Test bio',
        'location' => 'Test Location',
        'website' => 'https://example.com',
    ]);

    expect($profile->bio)->toBe('Test bio')
        ->and($profile->location)->toBe('Test Location')
        ->and($profile->website)->toBe('https://example.com')
        ->and($profile->user_id)->toBe($user->id);
});

test('profile belongs to user', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    expect($profile->user)->toBeInstanceOf(User::class)
        ->and($profile->user->id)->toBe($user->id);
});
