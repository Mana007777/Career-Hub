<?php

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;

test('any user can view any post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $policy = new PostPolicy();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->view($user, $post))->toBeTrue();
});

test('any user can create posts', function () {
    $user = User::factory()->create();
    $policy = new PostPolicy();

    expect($policy->create($user))->toBeTrue();
});

test('user can update their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $policy = new PostPolicy();

    expect($policy->update($user, $post))->toBeTrue();
});

test('user cannot update other users post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);
    $policy = new PostPolicy();

    expect($policy->update($user, $post))->toBeFalse();
});

test('admin can update any post', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $policy = new PostPolicy();

    expect($policy->update($admin, $post))->toBeTrue();
});

test('user can delete their own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $policy = new PostPolicy();

    expect($policy->delete($user, $post))->toBeTrue();
});

test('user cannot delete other users post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);
    $policy = new PostPolicy();

    expect($policy->delete($user, $post))->toBeFalse();
});

test('admin can delete any post', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $policy = new PostPolicy();

    expect($policy->delete($admin, $post))->toBeTrue();
});

test('only admin can restore posts', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $post = Post::factory()->create();
    $policy = new PostPolicy();

    expect($policy->restore($user, $post))->toBeFalse();
    expect($policy->restore($admin, $post))->toBeTrue();
});

test('only admin can force delete posts', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $post = Post::factory()->create();
    $policy = new PostPolicy();

    expect($policy->forceDelete($user, $post))->toBeFalse();
    expect($policy->forceDelete($admin, $post))->toBeTrue();
});
