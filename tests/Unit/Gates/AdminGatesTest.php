<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('is-admin gate allows admin user', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    
    expect(Gate::forUser($admin)->allows('is-admin'))->toBeTrue();
});

test('is-admin gate denies regular user', function () {
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($user)->denies('is-admin'))->toBeTrue();
});

test('manage-posts gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('manage-posts'))->toBeTrue();
    expect(Gate::forUser($user)->denies('manage-posts'))->toBeTrue();
});

test('manage-users gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('manage-users'))->toBeTrue();
    expect(Gate::forUser($user)->denies('manage-users'))->toBeTrue();
});

test('manage-comments gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('manage-comments'))->toBeTrue();
    expect(Gate::forUser($user)->denies('manage-comments'))->toBeTrue();
});

test('manage-reports gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('manage-reports'))->toBeTrue();
    expect(Gate::forUser($user)->denies('manage-reports'))->toBeTrue();
});

test('view-admin-panel gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('view-admin-panel'))->toBeTrue();
    expect(Gate::forUser($user)->denies('view-admin-panel'))->toBeTrue();
});

test('delete-any-post gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('delete-any-post'))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete-any-post'))->toBeTrue();
});

test('delete-any-user gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('delete-any-user'))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete-any-user'))->toBeTrue();
});

test('delete-any-comment gate allows admin', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    $user = User::factory()->create(['is_admin' => false]);
    
    expect(Gate::forUser($admin)->allows('delete-any-comment'))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete-any-comment'))->toBeTrue();
});
