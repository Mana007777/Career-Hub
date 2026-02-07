<?php

use App\Models\Certification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('certification can be created', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'name' => 'AWS Certified',
        'issuer' => 'Amazon',
        'issue_date' => now()->subYear(),
        'expires_at' => now()->addYear(),
    ]);

    expect($certification->name)->toBe('AWS Certified')
        ->and($certification->issuer)->toBe('Amazon')
        ->and($certification->user_id)->toBe($user->id);
});

test('certification belongs to user', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    expect($certification->user)->toBeInstanceOf(User::class)
        ->and($certification->user->id)->toBe($user->id);
});

test('certification dates are cast to date', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'issue_date' => '2023-01-01',
        'expires_at' => '2024-01-01',
    ]);

    expect($certification->issue_date)->toBeInstanceOf(\DateTimeInterface::class)
        ->and($certification->expires_at)->toBeInstanceOf(\DateTimeInterface::class);
});
