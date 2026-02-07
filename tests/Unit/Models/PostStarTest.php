<?php

use App\Models\PostStar;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post star can be created', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $star = PostStar::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);

    expect($star->post_id)->toBe($post->id)
        ->and($star->user_id)->toBe($user->id);
});

test('post star belongs to post', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $star = PostStar::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($star->post)->toBeInstanceOf(Post::class)
        ->and($star->post->id)->toBe($post->id);
});

test('post star belongs to user', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $star = PostStar::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($star->user)->toBeInstanceOf(User::class)
        ->and($star->user->id)->toBe($user->id);
});
