<?php

use App\Livewire\UserProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('user profile component can be rendered', function () {
    $user = User::factory()->create(['username' => 'johndoe']);
    
    Livewire::test(UserProfile::class, ['username' => 'johndoe'])
        ->assertSuccessful();
});

test('user profile component loads user data', function () {
    $user = User::factory()->create(['username' => 'johndoe']);
    
    Livewire::test(UserProfile::class, ['username' => 'johndoe'])
        ->assertSet('user.id', $user->id)
        ->assertSet('user.username', 'johndoe');
});

test('user profile component can toggle follow', function () {
    $user1 = User::factory()->create(['username' => 'user1']);
    $user2 = User::factory()->create(['username' => 'user2']);
    
    Livewire::actingAs($user1)
        ->test(UserProfile::class, ['username' => 'user2'])
        ->assertSet('isFollowing', false)
        ->call('toggleFollow')
        ->assertSet('isFollowing', true);
});

test('user profile component can open followers modal', function () {
    $user = User::factory()->create(['username' => 'johndoe']);
    
    Livewire::test(UserProfile::class, ['username' => 'johndoe'])
        ->assertSet('showFollowersModal', false)
        ->call('openFollowersModal')
        ->assertSet('showFollowersModal', true);
});

test('user profile component can open following modal', function () {
    $user = User::factory()->create(['username' => 'johndoe']);
    
    Livewire::test(UserProfile::class, ['username' => 'johndoe'])
        ->assertSet('showFollowingModal', false)
        ->call('openFollowingModal')
        ->assertSet('showFollowingModal', true);
});
