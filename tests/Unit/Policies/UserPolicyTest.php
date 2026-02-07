<?php

use App\Models\User;
use App\Policies\UserPolicy;

test('any user can view any user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->view($user, $otherUser))->toBeTrue();
});

test('any user can create users', function () {
    $user = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->create($user))->toBeTrue();
});

test('user can update their own profile', function () {
    $user = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->update($user, $user))->toBeTrue();
});

test('user cannot update other users profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->update($user, $otherUser))->toBeFalse();
});

test('admin can update any user', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->update($admin, $user))->toBeTrue();
});

test('user cannot delete users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->delete($user, $otherUser))->toBeFalse();
});

test('admin can delete any user', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->delete($admin, $user))->toBeTrue();
});

test('admin cannot delete themselves', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $policy = new UserPolicy();

    expect($policy->delete($admin, $admin))->toBeFalse();
});

test('only admin can restore users', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->restore($user, $otherUser))->toBeFalse();
    expect($policy->restore($admin, $otherUser))->toBeTrue();
});

test('only admin can force delete users', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->forceDelete($user, $otherUser))->toBeFalse();
    expect($policy->forceDelete($admin, $otherUser))->toBeTrue();
});

test('admin cannot force delete themselves', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $policy = new UserPolicy();

    expect($policy->forceDelete($admin, $admin))->toBeFalse();
});

test('only admin can suspend users', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $otherUser = User::factory()->create();
    $policy = new UserPolicy();

    expect($policy->suspend($user, $otherUser))->toBeFalse();
    expect($policy->suspend($admin, $otherUser))->toBeTrue();
});

test('admin cannot suspend themselves', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $policy = new UserPolicy();

    expect($policy->suspend($admin, $admin))->toBeFalse();
});

test('only admin can view admin panel', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $policy = new UserPolicy();

    expect($policy->viewAdminPanel($user))->toBeFalse();
    expect($policy->viewAdminPanel($admin))->toBeTrue();
});
