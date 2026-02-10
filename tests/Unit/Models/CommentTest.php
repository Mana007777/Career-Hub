<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('comment can be created', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'Test comment',
    ]);

    expect($comment->content)->toBe('Test comment')
        ->and($comment->post_id)->toBe($post->id)
        ->and($comment->user_id)->toBe($user->id);
});

test('comment belongs to post', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($comment->post)->toBeInstanceOf(Post::class)
        ->and($comment->post->id)->toBe($post->id);
});

test('comment belongs to user', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($comment->user)->toBeInstanceOf(User::class)
        ->and($comment->user->id)->toBe($user->id);
});

test('comment has many replies', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $parentComment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    Comment::factory()->count(2)->create(['post_id' => $post->id, 'user_id' => $user->id, 'parent_id' => $parentComment->id]);

    expect($parentComment->replies)->toHaveCount(2);
});

test('comment has parent comment', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $parentComment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    $reply = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id, 'parent_id' => $parentComment->id]);

    expect($reply->parent)->toBeInstanceOf(Comment::class)
        ->and($reply->parent->id)->toBe($parentComment->id);
});

// Likes feature removed: we no longer test comment likes relationships.
