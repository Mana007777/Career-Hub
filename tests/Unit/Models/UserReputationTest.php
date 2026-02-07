<?php

use App\Models\UserReputation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user reputation can be created', function () {
    $user = User::factory()->create();
    $reputation = UserReputation::factory()->create([
        'user_id' => $user->id,
        'score' => 100,
        'level' => 5,
    ]);

    expect($reputation->user_id)->toBe($user->id)
        ->and($reputation->score)->toBe(100)
        ->and($reputation->level)->toBe(5);
});

test('user reputation belongs to user', function () {
    $user = User::factory()->create();
    $reputation = UserReputation::factory()->create(['user_id' => $user->id]);

    expect($reputation->user)->toBeInstanceOf(User::class)
        ->and($reputation->user->id)->toBe($user->id);
});
