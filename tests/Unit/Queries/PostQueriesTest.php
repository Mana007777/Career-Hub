<?php

use App\Queries\PostQueries;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post queries can search posts', function () {
    $user = User::factory()->create();
    Post::factory()->create(['user_id' => $user->id, 'title' => 'PHP Developer']);
    
    $queries = new PostQueries();
    $results = $queries->search('PHP', 10, $user->id);
    
    expect($results->count())->toBeGreaterThan(0);
});

test('post queries can get popular posts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $post->likedBy()->attach($user->id);
    
    $queries = new PostQueries();
    $results = $queries->getPopular(10, $user->id);
    
    expect($results)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post queries can get following posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    Post::factory()->create(['user_id' => $user2->id]);
    
    $queries = new PostQueries();
    $results = $queries->getFollowingForUser($user1->id, 10);
    
    expect($results)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post queries can find post by id', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $queries = new PostQueries();
    $found = $queries->findById($post->id);
    
    expect($found)->toBeInstanceOf(Post::class)
        ->and($found->id)->toBe($post->id);
});

test('post queries can clear post cache', function () {
    $queries = new PostQueries();
    
    expect(fn() => $queries->clearPostCache(1))->not->toThrow(\Exception::class);
});

test('post queries can clear all post caches', function () {
    $queries = new PostQueries();
    
    expect(fn() => $queries->clearAllPostCaches())->not->toThrow(\Exception::class);
});

test('post queries can apply filters', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $post->tags()->attach($tag->id);
    
    $queries = new PostQueries();
    $results = $queries->getPopular(10, $user->id, ['tags' => [$tag->id]]);
    
    expect($results)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});
