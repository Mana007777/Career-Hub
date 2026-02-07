<?php

use App\Models\PostLike;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post like can be created', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $like = PostLike::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);

    expect($like->post_id)->toBe($post->id)
        ->and($like->user_id)->toBe($user->id);
});

test('post like belongs to post', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $like = PostLike::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($like->post)->toBeInstanceOf(Post::class)
        ->and($like->post->id)->toBe($post->id);
});

test('post like belongs to user', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $like = PostLike::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($like->user)->toBeInstanceOf(User::class)
        ->and($like->user->id)->toBe($user->id);
});
