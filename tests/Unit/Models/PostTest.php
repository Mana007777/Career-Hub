<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\Share;
use App\Models\UserNotification;
use App\Models\PostCv;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('post can be created', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Test Post',
        'content' => 'Test Content',
    ]);

    expect($post->title)->toBe('Test Post')
        ->and($post->content)->toBe('Test Content')
        ->and($post->user_id)->toBe($user->id);
});

test('post belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    expect($post->user)->toBeInstanceOf(User::class)
        ->and($post->user->id)->toBe($user->id);
});

test('post has many likes', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $post->likedBy()->attach($user->id);

    expect($post->likedBy)->toHaveCount(1);
});

test('post has many stars', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $post->starredBy()->attach($user->id);

    expect($post->starredBy)->toHaveCount(1);
});

test('post has many comments', function () {
    $post = Post::factory()->create();
    Comment::factory()->count(3)->create(['post_id' => $post->id]);

    expect($post->comments)->toHaveCount(3);
});

test('post belongs to many tags', function () {
    $post = Post::factory()->create();
    $tag = Tag::factory()->create();
    $post->tags()->attach($tag->id);

    expect($post->tags)->toHaveCount(1);
});

test('post belongs to many specialties', function () {
    $post = Post::factory()->create();
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);
    $post->specialties()->attach($specialty->id, ['sub_specialty_id' => $subSpecialty->id]);

    expect($post->specialties)->toHaveCount(1);
});

test('post has many shares', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    Share::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    expect($post->shares)->toHaveCount(1);
});

test('post has many notifications', function () {
    $post = Post::factory()->create();
    UserNotification::factory()->count(2)->create(['post_id' => $post->id]);

    expect($post->notifications)->toHaveCount(2);
});

test('post has many cvs', function () {
    $post = Post::factory()->create();
    PostCv::factory()->count(2)->create(['post_id' => $post->id]);

    expect($post->cvs)->toHaveCount(2);
});

test('post has slug attribute', function () {
    $post = Post::factory()->create(['title' => 'Test Post Title']);

    expect($post->slug)->toBeString()
        ->and($post->slug)->toContain('test-post-title')
        ->and($post->slug)->toContain((string)$post->id);
});

test('post slug falls back to content when title is empty', function () {
    $post = Post::factory()->create(['title' => '', 'content' => 'Test Content']);

    expect($post->slug)->toBeString()
        ->and($post->slug)->toContain('test-content');
});

test('post slug uses default when both title and content are empty', function () {
    $post = Post::factory()->create(['title' => '', 'content' => '']);

    expect($post->slug)->toBeString()
        ->and($post->slug)->toContain('post');
});
