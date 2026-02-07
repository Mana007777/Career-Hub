<?php

use App\Models\Resume;
use App\Models\User;
use App\Models\ResumeSection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('resume can be created', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create([
        'user_id' => $user->id,
        'title' => 'My Resume',
    ]);

    expect($resume->title)->toBe('My Resume')
        ->and($resume->user_id)->toBe($user->id);
});

test('resume belongs to user', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);

    expect($resume->user)->toBeInstanceOf(User::class)
        ->and($resume->user->id)->toBe($user->id);
});

test('resume has many sections', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);
    ResumeSection::factory()->count(3)->create(['resume_id' => $resume->id]);

    expect($resume->sections)->toHaveCount(3);
});
