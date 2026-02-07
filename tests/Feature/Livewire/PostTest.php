<?php

use App\Livewire\Post;
use App\Models\User;
use App\Models\Post as PostModel;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('post component can be rendered', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->assertSuccessful();
});

test('post component can toggle create form', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->assertSet('showCreateForm', false)
        ->call('toggleCreateForm')
        ->assertSet('showCreateForm', true)
        ->call('toggleCreateForm')
        ->assertSet('showCreateForm', false);
});

test('post component can set feed mode', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->call('setFeedMode', 'popular')
        ->assertSet('feedMode', 'popular')
        ->call('setFeedMode', 'following')
        ->assertSet('feedMode', 'following');
});

test('post component can add specialty', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->set('specialtyName', 'Technology')
        ->set('subSpecialtyName', 'Web Development')
        ->call('addSpecialty')
        ->assertSet('specialties', function ($specialties) {
            return count($specialties) === 1 
                && $specialties[0]['specialty_name'] === 'Technology'
                && $specialties[0]['sub_specialty_name'] === 'Web Development';
        });
});

test('post component can add tag', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->set('tagName', 'PHP')
        ->call('addTag')
        ->assertSet('tags', function ($tags) {
            return count($tags) === 1 && $tags[0]['name'] === 'PHP';
        });
});

test('post component can remove specialty', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->set('specialtyName', 'Technology')
        ->set('subSpecialtyName', 'Web Development')
        ->call('addSpecialty')
        ->call('removeSpecialty', 0)
        ->assertSet('specialties', []);
});

test('post component can remove tag', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->set('tagName', 'PHP')
        ->call('addTag')
        ->call('removeTag', 0)
        ->assertSet('tags', []);
});

test('post component can toggle filters', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->assertSet('showFilters', false)
        ->call('toggleFilters')
        ->assertSet('showFilters', true);
});

test('post component can clear filters', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Post::class)
        ->set('sortOrder', 'asc')
        ->set('selectedTags', '1')
        ->set('selectedSpecialties', '1')
        ->set('selectedJobType', 'full-time')
        ->call('clearFilters')
        ->assertSet('sortOrder', 'desc')
        ->assertSet('selectedTags', '')
        ->assertSet('selectedSpecialties', '')
        ->assertSet('selectedJobType', '');
});
