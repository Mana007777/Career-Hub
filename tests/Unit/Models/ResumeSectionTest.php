<?php

use App\Models\ResumeSection;
use App\Models\Resume;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('resume section can be created', function () {
    $user = \App\Models\User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);
    $section = ResumeSection::factory()->create([
        'resume_id' => $resume->id,
        'type' => 'experience',
        'content' => 'Worked at company X',
    ]);

    expect($section->type)->toBe('experience')
        ->and($section->content)->toBe('Worked at company X')
        ->and($section->resume_id)->toBe($resume->id);
});

test('resume section belongs to resume', function () {
    $user = \App\Models\User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);
    $section = ResumeSection::factory()->create(['resume_id' => $resume->id]);

    expect($section->resume)->toBeInstanceOf(Resume::class)
        ->and($section->resume->id)->toBe($resume->id);
});
