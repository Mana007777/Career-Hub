<?php

use App\Repositories\BlockRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('block repository can get blocked users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->blockedUsers()->attach($user2->id);
    
    $repository = new BlockRepository();
    $blocked = $repository->getBlockedUsers($user1->id);
    
    expect($blocked)->toHaveCount(1);
});

test('block repository can check if blocked', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->blockedUsers()->attach($user2->id);
    
    $repository = new BlockRepository();
    $isBlocked = $repository->isBlocked($user1->id, $user2->id);
    
    expect($isBlocked)->toBeTrue();
});

test('block repository can get blocked user ids', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->blockedUsers()->attach($user2->id);
    
    $repository = new BlockRepository();
    $ids = $repository->getBlockedUserIds($user1->id);
    
    expect($ids)->toContain($user2->id);
});

test('block repository can get blocked by user ids', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->blockedUsers()->attach($user2->id);
    
    $repository = new BlockRepository();
    $ids = $repository->getBlockedByUserIds($user2->id);
    
    expect($ids)->toContain($user1->id);
});

test('block repository can clear block cache', function () {
    $repository = new BlockRepository();
    
    expect(fn() => $repository->clearBlockCache(1))->not->toThrow(\Exception::class);
});
