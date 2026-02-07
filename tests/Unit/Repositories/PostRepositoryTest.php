<?php

use App\Repositories\PostRepository;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post repository can get all posts', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_id' => $user->id]);
    
    $repository = new PostRepository();
    $posts = $repository->getAll(10, $user->id);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($posts->count())->toBeGreaterThan(0);
});

test('post repository can get posts by user id', function () {
    $user = User::factory()->create();
    Post::factory()->count(2)->create(['user_id' => $user->id]);
    
    $repository = new PostRepository();
    $posts = $repository->getByUserId($user->id, 10);
    
    expect($posts)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($posts->count())->toBe(2);
});

test('post repository can create post', function () {
    $user = User::factory()->create();
    
    $repository = new PostRepository();
    $post = $repository->create([
        'user_id' => $user->id,
        'title' => 'Test Post',
        'content' => 'Test Content',
    ]);
    
    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->title)->toBe('Test Post');
});

test('post repository can update post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $repository = new PostRepository();
    $updated = $repository->update($post, ['title' => 'Updated Title']);
    
    expect($updated)->toBeTrue()
        ->and($post->fresh()->title)->toBe('Updated Title');
});

test('post repository can delete post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $repository = new PostRepository();
    $deleted = $repository->delete($post);
    
    expect($deleted)->toBeTrue()
        ->and(Post::find($post->id))->toBeNull();
});

test('post repository can find post by id', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    $repository = new PostRepository();
    $found = $repository->findById($post->id);
    
    expect($found)->toBeInstanceOf(Post::class)
        ->and($found->id)->toBe($post->id);
});

test('post repository can get empty paginated result', function () {
    $repository = new PostRepository();
    $result = $repository->getEmptyPaginated(10);
    
    expect($result)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($result->count())->toBe(0);
});
