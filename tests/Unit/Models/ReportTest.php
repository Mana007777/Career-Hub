<?php

use App\Models\Report;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('report can be created', function () {
    $reporter = User::factory()->create();
    $post = Post::factory()->create();
    $report = Report::factory()->create([
        'reporter_id' => $reporter->id,
        'target_type' => Post::class,
        'target_id' => $post->id,
        'reason' => 'Spam',
        'status' => 'pending',
    ]);

    expect($report->reporter_id)->toBe($reporter->id)
        ->and($report->target_type)->toBe(Post::class)
        ->and($report->target_id)->toBe($post->id)
        ->and($report->reason)->toBe('Spam')
        ->and($report->status)->toBe('pending');
});

test('report belongs to reporter', function () {
    $reporter = User::factory()->create();
    $post = Post::factory()->create();
    $report = Report::factory()->create([
        'reporter_id' => $reporter->id,
        'target_type' => Post::class,
        'target_id' => $post->id,
    ]);

    expect($report->reporter)->toBeInstanceOf(User::class)
        ->and($report->reporter->id)->toBe($reporter->id);
});

test('report has morph to target', function () {
    $reporter = User::factory()->create();
    $post = Post::factory()->create();
    $report = Report::factory()->create([
        'reporter_id' => $reporter->id,
        'target_type' => Post::class,
        'target_id' => $post->id,
    ]);

    expect($report->target)->toBeInstanceOf(Post::class)
        ->and($report->target->id)->toBe($post->id);
});
