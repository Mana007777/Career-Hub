<?php

use App\Models\CareerJob;
use App\Models\Company;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\JobApplication;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('career job can be created', function () {
    $company = Company::factory()->create();
    $job = CareerJob::factory()->create([
        'company_id' => $company->id,
        'title' => 'Software Engineer',
        'description' => 'Job description',
        'location' => 'Remote',
        'job_type' => 'full-time',
    ]);

    expect($job->title)->toBe('Software Engineer')
        ->and($job->description)->toBe('Job description')
        ->and($job->location)->toBe('Remote')
        ->and($job->job_type)->toBe('full-time');
});

test('career job belongs to company', function () {
    $company = Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);

    expect($job->company)->toBeInstanceOf(Company::class)
        ->and($job->company->id)->toBe($company->id);
});

test('career job belongs to specialty', function () {
    $company = Company::factory()->create();
    $specialty = Specialty::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id, 'specialty_id' => $specialty->id]);

    expect($job->specialty)->toBeInstanceOf(Specialty::class)
        ->and($job->specialty->id)->toBe($specialty->id);
});

test('career job belongs to sub specialty', function () {
    $company = Company::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id, 'sub_specialty_id' => $subSpecialty->id]);

    expect($job->subSpecialty)->toBeInstanceOf(SubSpecialty::class)
        ->and($job->subSpecialty->id)->toBe($subSpecialty->id);
});

test('career job has many applications', function () {
    $company = Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    JobApplication::factory()->count(3)->create(['job_id' => $job->id]);

    expect($job->applications)->toHaveCount(3);
});

test('career job belongs to many tags', function () {
    $company = Company::factory()->create();
    $job = CareerJob::factory()->create(['company_id' => $company->id]);
    $tag = Tag::factory()->create();
    $job->tags()->attach($tag->id);

    expect($job->tags)->toHaveCount(1);
});
