<?php

use App\Models\CommentLike;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('comment like can be created', function () {
    $post = \App\Models\Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    $like = CommentLike::factory()->create([
        'comment_id' => $comment->id,
        'user_id' => $user->id,
    ]);

    expect($like->comment_id)->toBe($comment->id)
        ->and($like->user_id)->toBe($user->id);
});

test('comment like belongs to comment', function () {
    $post = \App\Models\Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    $like = CommentLike::factory()->create(['comment_id' => $comment->id, 'user_id' => $user->id]);

    expect($like->comment)->toBeInstanceOf(Comment::class)
        ->and($like->comment->id)->toBe($comment->id);
});

test('comment like belongs to user', function () {
    $post = \App\Models\Post::factory()->create();
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    $like = CommentLike::factory()->create(['comment_id' => $comment->id, 'user_id' => $user->id]);

    expect($like->user)->toBeInstanceOf(User::class)
        ->and($like->user->id)->toBe($user->id);
});
