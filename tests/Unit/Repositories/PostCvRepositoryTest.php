<?php

use App\Repositories\PostCvRepository;
use App\Models\Post;
use App\Models\PostCv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post cv repository can get cvs for user posts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    PostCv::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    
    $repository = new PostCvRepository();
    $cvs = $repository->getCvsForUserPosts($user->id, 10);
    
    expect($cvs)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($cvs->count())->toBe(1);
});

test('post cv repository can find cv by id', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $postCv = PostCv::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    
    $repository = new PostCvRepository();
    $found = $repository->findById($postCv->id);
    
    expect($found)->toBeInstanceOf(PostCv::class)
        ->and($found->id)->toBe($postCv->id);
});

test('post cv repository can check if user uploaded cv', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    PostCv::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
    
    $repository = new PostCvRepository();
    $hasUploaded = $repository->hasUserUploadedCv($post->id, $user->id);
    
    expect($hasUploaded)->toBeTrue();
});
