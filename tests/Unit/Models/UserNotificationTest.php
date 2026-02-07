<?php

use App\Models\UserNotification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user notification can be created', function () {
    $user = User::factory()->create();
    $sourceUser = User::factory()->create();
    $post = Post::factory()->create();
    $notification = UserNotification::factory()->create([
        'user_id' => $user->id,
        'source_user_id' => $sourceUser->id,
        'type' => 'like',
        'post_id' => $post->id,
        'message' => 'User liked your post',
        'is_read' => false,
    ]);

    expect($notification->user_id)->toBe($user->id)
        ->and($notification->source_user_id)->toBe($sourceUser->id)
        ->and($notification->type)->toBe('like')
        ->and($notification->message)->toBe('User liked your post')
        ->and($notification->is_read)->toBeFalse();
});

test('user notification belongs to user', function () {
    $user = User::factory()->create();
    $notification = UserNotification::factory()->create(['user_id' => $user->id]);

    expect($notification->user)->toBeInstanceOf(User::class)
        ->and($notification->user->id)->toBe($user->id);
});

test('user notification belongs to source user', function () {
    $user = User::factory()->create();
    $sourceUser = User::factory()->create();
    $notification = UserNotification::factory()->create([
        'user_id' => $user->id,
        'source_user_id' => $sourceUser->id,
    ]);

    expect($notification->sourceUser)->toBeInstanceOf(User::class)
        ->and($notification->sourceUser->id)->toBe($sourceUser->id);
});

test('user notification belongs to post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $notification = UserNotification::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($notification->post)->toBeInstanceOf(Post::class)
        ->and($notification->post->id)->toBe($post->id);
});

test('user notification is_read is cast to boolean', function () {
    $user = User::factory()->create();
    $notification = UserNotification::factory()->create([
        'user_id' => $user->id,
        'is_read' => 1,
    ]);

    expect($notification->is_read)->toBeBool()
        ->and($notification->is_read)->toBeTrue();
});
