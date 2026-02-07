<?php

use App\Models\Verification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('verification can be created', function () {
    $user = User::factory()->create();
    $verification = Verification::factory()->create([
        'user_id' => $user->id,
        'type' => 'identity',
        'status' => 'pending',
        'document_url' => 'https://example.com/doc.pdf',
    ]);

    expect($verification->type)->toBe('identity')
        ->and($verification->status)->toBe('pending')
        ->and($verification->document_url)->toBe('https://example.com/doc.pdf')
        ->and($verification->user_id)->toBe($user->id);
});

test('verification belongs to user', function () {
    $user = User::factory()->create();
    $verification = Verification::factory()->create(['user_id' => $user->id]);

    expect($verification->user)->toBeInstanceOf(User::class)
        ->and($verification->user->id)->toBe($user->id);
});
