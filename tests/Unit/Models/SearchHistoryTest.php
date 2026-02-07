<?php

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('search history can be created', function () {
    $user = User::factory()->create();
    $searchHistory = SearchHistory::factory()->create([
        'user_id' => $user->id,
        'keyword' => 'PHP developer',
    ]);

    expect($searchHistory->keyword)->toBe('PHP developer')
        ->and($searchHistory->user_id)->toBe($user->id);
});

test('search history belongs to user', function () {
    $user = User::factory()->create();
    $searchHistory = SearchHistory::factory()->create(['user_id' => $user->id]);

    expect($searchHistory->user)->toBeInstanceOf(User::class)
        ->and($searchHistory->user->id)->toBe($user->id);
});
