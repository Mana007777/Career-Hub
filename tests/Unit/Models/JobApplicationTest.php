<?php

use App\Models\JobApplication;
use App\Models\CareerJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('job application can be created', function () {
    $user = User::factory()->create();
    $company = \App\Models\Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    $application = JobApplication::factory()->create([
        'job_id' => $job->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    expect($application->job_id)->toBe($job->id)
        ->and($application->user_id)->toBe($user->id)
        ->and($application->status)->toBe('pending');
});

test('job application belongs to job', function () {
    $user = User::factory()->create();
    $company = \App\Models\Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id]);

    expect($application->job)->toBeInstanceOf(CareerJob::class)
        ->and($application->job->id)->toBe($job->id);
});

test('job application belongs to user', function () {
    $user = User::factory()->create();
    $company = \App\Models\Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id, 'user_id' => $user->id]);

    expect($application->user)->toBeInstanceOf(User::class)
        ->and($application->user->id)->toBe($user->id);
});
