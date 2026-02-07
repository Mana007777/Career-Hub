<?php

use App\Models\Share;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('share can be created', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $share = Share::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($share->user_id)->toBe($user->id)
        ->and($share->post_id)->toBe($post->id);
});

test('share belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $share = Share::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

    expect($share->user)->toBeInstanceOf(User::class)
        ->and($share->user->id)->toBe($user->id);
});

test('share belongs to post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $share = Share::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

    expect($share->post)->toBeInstanceOf(Post::class)
        ->and($share->post->id)->toBe($post->id);
});
