<?php

use App\Services\PostService;
use App\Repositories\PostRepository;
use App\Queries\PostQueries;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

test('post service can get all posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_id' => $user->id]);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $posts = $service->getAllPosts(10);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post service can get popular posts', function () {
    $user = User::factory()->create();
    Post::factory()->create(['user_id' => $user->id]);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $posts = $service->getPopularPosts(10);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post service can get user posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(2)->create(['user_id' => $user->id]);
    
    Auth::login($user);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $posts = $service->getUserPosts(10);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($posts->count())->toBe(2);
});

test('post service can get following posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->following()->attach($user2->id);
    Post::factory()->create(['user_id' => $user2->id]);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $posts = $service->getFollowingPosts(10);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post service can get post by id', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $found = $service->getPostById($post->id);
    
    expect($found)->toBeInstanceOf(Post::class)
        ->and($found->id)->toBe($post->id);
});

test('post service can search posts', function () {
    $user = User::factory()->create();
    Post::factory()->create(['user_id' => $user->id, 'title' => 'PHP Developer']);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $results = $service->searchPosts('PHP', 10);
    
    expect($results)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
});

test('post service can get media url', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id, 'media' => 'test.jpg']);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $url = $service->getMediaUrl($post);
    
    expect($url)->toBeString();
});

test('post service returns null for media url when no media', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id, 'media' => null]);
    
    $service = new PostService(new PostRepository(), new PostQueries());
    $url = $service->getMediaUrl($post);
    
    expect($url)->toBeNull();
});
