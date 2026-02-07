<?php

use App\Models\Specialty;
use App\Models\SubSpecialty;
use App\Models\CareerJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('specialty can be created', function () {
    $specialty = Specialty::factory()->create(['name' => 'Technology']);

    expect($specialty->name)->toBe('Technology');
});

test('specialty has many sub specialties', function () {
    $specialty = Specialty::factory()->create();
    SubSpecialty::factory()->count(3)->create(['specialty_id' => $specialty->id]);

    expect($specialty->subSpecialties)->toHaveCount(3);
});

test('specialty belongs to many users', function () {
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);
    $user = User::factory()->create();
    $user->specialties()->attach($specialty->id, ['sub_specialty_id' => $subSpecialty->id]);

    expect($specialty->users)->toHaveCount(1);
});

test('specialty has many jobs', function () {
    $company = \App\Models\Company::factory()->create();
    $specialty = Specialty::factory()->create();
    CareerJob::factory()->count(2)->create(['company_id' => $company->id, 'specialty_id' => $specialty->id]);

    expect($specialty->jobs)->toHaveCount(2);
});
