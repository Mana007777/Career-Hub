<?php

use App\Models\Tag;
use App\Models\Post;
use App\Models\CareerJob;
use App\Models\TagStat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tag can be created', function () {
    $tag = Tag::factory()->create(['name' => 'PHP']);

    expect($tag->name)->toBe('PHP');
});

test('tag belongs to many posts', function () {
    $tag = Tag::factory()->create();
    $post = Post::factory()->create();
    $post->tags()->attach($tag->id);

    expect($tag->posts)->toHaveCount(1);
});

test('tag belongs to many jobs', function () {
    $tag = Tag::factory()->create();
    $company = \App\Models\Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    $job->tags()->attach($tag->id);

    expect($tag->jobs)->toHaveCount(1);
});

test('tag has one stat', function () {
    $tag = Tag::factory()->create();
    TagStat::factory()->create(['tag_id' => $tag->id]);

    expect($tag->stats)->toBeInstanceOf(TagStat::class);
});
