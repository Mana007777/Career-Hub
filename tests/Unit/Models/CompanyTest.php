<?php

use App\Models\Company;
use App\Models\User;
use App\Models\CareerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('company can be created', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create([
        'user_id' => $user->id,
        'name' => 'Test Company',
        'industry' => 'Technology',
    ]);

    expect($company->name)->toBe('Test Company')
        ->and($company->industry)->toBe('Technology')
        ->and($company->user_id)->toBe($user->id);
});

test('company belongs to user', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);

    expect($company->user)->toBeInstanceOf(User::class)
        ->and($company->user->id)->toBe($user->id);
});

test('company has many jobs', function () {
    $company = Company::factory()->create();
    CareerJob::factory()->count(3)->create(['company_id' => $company->id]);

    expect($company->jobs)->toHaveCount(3);
});
