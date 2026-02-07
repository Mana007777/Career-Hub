<?php

use App\Models\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;

test('any user can view any comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create();
    $policy = new CommentPolicy();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->view($user, $comment))->toBeTrue();
});

test('any user can create comments', function () {
    $user = User::factory()->create();
    $policy = new CommentPolicy();

    expect($policy->create($user))->toBeTrue();
});

test('user can update their own comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    $policy = new CommentPolicy();

    expect($policy->update($user, $comment))->toBeTrue();
});

test('user cannot update other users comment', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $otherUser->id]);
    $policy = new CommentPolicy();

    expect($policy->update($user, $comment))->toBeFalse();
});

test('admin can update any comment', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    $policy = new CommentPolicy();

    expect($policy->update($admin, $comment))->toBeTrue();
});

test('user can delete their own comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    $policy = new CommentPolicy();

    expect($policy->delete($user, $comment))->toBeTrue();
});

test('user cannot delete other users comment', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $otherUser->id]);
    $policy = new CommentPolicy();

    expect($policy->delete($user, $comment))->toBeFalse();
});

test('admin can delete any comment', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    $policy = new CommentPolicy();

    expect($policy->delete($admin, $comment))->toBeTrue();
});

test('only admin can restore comments', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $comment = Comment::factory()->create();
    $policy = new CommentPolicy();

    expect($policy->restore($user, $comment))->toBeFalse();
    expect($policy->restore($admin, $comment))->toBeTrue();
});

test('only admin can force delete comments', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $comment = Comment::factory()->create();
    $policy = new CommentPolicy();

    expect($policy->forceDelete($user, $comment))->toBeFalse();
    expect($policy->forceDelete($admin, $comment))->toBeTrue();
});
