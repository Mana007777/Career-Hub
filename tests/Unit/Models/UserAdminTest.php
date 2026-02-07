<?php

use App\Models\User;

test('user isAdmin returns true for admin user with is_admin flag', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    
    expect($admin->isAdmin())->toBeTrue();
});

test('user isAdmin returns true for test@example.com email', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => false]);
    
    expect($admin->isAdmin())->toBeTrue();
});

test('user isAdmin returns false for regular user', function () {
    $user = User::factory()->create(['is_admin' => false, 'email' => 'user@example.com']);
    
    expect($user->isAdmin())->toBeFalse();
});

test('user isAdmin returns true when both conditions are met', function () {
    $admin = User::factory()->create(['email' => 'test@example.com', 'is_admin' => true]);
    
    expect($admin->isAdmin())->toBeTrue();
});
