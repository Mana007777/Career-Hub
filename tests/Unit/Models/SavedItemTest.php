<?php

use App\Models\SavedItem;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('saved item can be created', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $savedItem = SavedItem::factory()->create([
        'user_id' => $user->id,
        'item_type' => Post::class,
        'item_id' => $post->id,
    ]);

    expect($savedItem->user_id)->toBe($user->id)
        ->and($savedItem->item_type)->toBe(Post::class)
        ->and($savedItem->item_id)->toBe($post->id);
});

test('saved item belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $savedItem = SavedItem::factory()->create([
        'user_id' => $user->id,
        'item_type' => Post::class,
        'item_id' => $post->id,
    ]);

    expect($savedItem->user)->toBeInstanceOf(User::class)
        ->and($savedItem->user->id)->toBe($user->id);
});

test('saved item has morph to item', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $savedItem = SavedItem::factory()->create([
        'user_id' => $user->id,
        'item_type' => Post::class,
        'item_id' => $post->id,
    ]);

    expect($savedItem->item)->toBeInstanceOf(Post::class)
        ->and($savedItem->item->id)->toBe($post->id);
});
