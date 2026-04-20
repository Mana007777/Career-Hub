<?php

use App\Actions\Post\TogglePostRepost;
use App\Models\Post;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('company receives notification when seeker reposts their post', function () {
    $company = User::factory()->create(['role' => 'company']);
    $seeker = User::factory()->create(['role' => 'seeker']);
    $post = Post::factory()->create(['user_id' => $company->id]);

    app(TogglePostRepost::class)->toggle($seeker, $post);

    expect(UserNotification::query()
        ->where('user_id', $company->id)
        ->where('type', 'post_reposted')
        ->where('post_id', $post->id)
        ->where('source_user_id', $seeker->id)
        ->exists())->toBeTrue();
});

test('removing repost does not send notification', function () {
    $company = User::factory()->create(['role' => 'company']);
    $seeker = User::factory()->create(['role' => 'seeker']);
    $post = Post::factory()->create(['user_id' => $company->id]);
    $toggle = app(TogglePostRepost::class);

    $toggle->toggle($seeker, $post);
    UserNotification::query()->where('user_id', $company->id)->delete();

    $toggle->toggle($seeker, $post);

    expect(UserNotification::query()
        ->where('user_id', $company->id)
        ->where('type', 'post_reposted')
        ->exists())->toBeFalse();
});
