<?php

use App\Models\PostCv;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post cv can be created', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $postCv = PostCv::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'cv_file' => 'cv.pdf',
        'original_filename' => 'my-cv.pdf',
        'message' => 'Please consider my application',
    ]);

    expect($postCv->post_id)->toBe($post->id)
        ->and($postCv->user_id)->toBe($user->id)
        ->and($postCv->cv_file)->toBe('cv.pdf')
        ->and($postCv->original_filename)->toBe('my-cv.pdf')
        ->and($postCv->message)->toBe('Please consider my application');
});

test('post cv belongs to post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $postCv = PostCv::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($postCv->post)->toBeInstanceOf(Post::class)
        ->and($postCv->post->id)->toBe($post->id);
});

test('post cv belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $postCv = PostCv::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($postCv->user)->toBeInstanceOf(User::class)
        ->and($postCv->user->id)->toBe($user->id);
});
