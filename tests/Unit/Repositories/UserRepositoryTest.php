<?php

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user repository can find user by username with counts', function () {
    $user = User::factory()->create(['username' => 'johndoe']);
    
    $repository = new UserRepository();
    $found = $repository->findByUsernameWithCounts('johndoe');
    
    expect($found)->toBeInstanceOf(User::class)
        ->and($found->username)->toBe('johndoe');
});

test('user repository can get followers with profile', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->followers()->attach($user2->id);
    
    $repository = new UserRepository();
    $followers = $repository->getFollowersWithProfile($user1);
    
    expect($followers)->toHaveCount(1);
});

test('user repository can get following with profile', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    
    $repository = new UserRepository();
    $following = $repository->getFollowingWithProfile($user1);
    
    expect($following)->toHaveCount(1);
});

test('user repository can find user by id', function () {
    $user = User::factory()->create();
    
    $repository = new UserRepository();
    $found = $repository->findById($user->id);
    
    expect($found)->toBeInstanceOf(User::class)
        ->and($found->id)->toBe($user->id);
});

test('user repository can get following ids', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    
    $repository = new UserRepository();
    $ids = $repository->getFollowingIds($user1);
    
    expect($ids)->toContain($user2->id);
});

test('user repository can check if following', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    
    $repository = new UserRepository();
    $isFollowing = $repository->isFollowing($user1, $user2);
    
    expect($isFollowing)->toBeTrue();
});

test('user repository can check if followed back', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->followers()->attach($user2->id);
    
    $repository = new UserRepository();
    $isFollowedBack = $repository->isFollowedBack($user1, $user2);
    
    expect($isFollowedBack)->toBeTrue();
});

test('user repository can search users', function () {
    $user = User::factory()->create(['username' => 'johndoe', 'name' => 'John Doe']);
    
    $repository = new UserRepository();
    $results = $repository->searchUsers('john', 10);
    
    expect($results)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($results->count())->toBeGreaterThan(0);
});

test('user repository can clear user cache', function () {
    $user = User::factory()->create();
    
    $repository = new UserRepository();
    
    expect(fn() => $repository->clearUserCache($user))->not->toThrow(\Exception::class);
});

test('user repository can clear follow cache', function () {
    $repository = new UserRepository();
    
    // Should not throw any exceptions
    $repository->clearFollowCache(1, 2);
    
    expect(true)->toBeTrue();
});
