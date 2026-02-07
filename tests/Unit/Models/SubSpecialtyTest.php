<?php

use App\Models\SubSpecialty;
use App\Models\Specialty;
use App\Models\CareerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sub specialty can be created', function () {
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create([
        'specialty_id' => $specialty->id,
        'name' => 'Web Development',
    ]);

    expect($subSpecialty->name)->toBe('Web Development')
        ->and($subSpecialty->specialty_id)->toBe($specialty->id);
});

test('sub specialty belongs to specialty', function () {
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);

    expect($subSpecialty->specialty)->toBeInstanceOf(Specialty::class)
        ->and($subSpecialty->specialty->id)->toBe($specialty->id);
});

test('sub specialty has many jobs', function () {
    $company = \App\Models\Company::factory()->create();
    $specialty = Specialty::factory()->create();
    $subSpecialty = SubSpecialty::factory()->create(['specialty_id' => $specialty->id]);
    CareerJob::factory()->count(2)->create([
        'company_id' => $company->id,
        'specialty_id' => $specialty->id,
        'sub_specialty_id' => $subSpecialty->id,
    ]);

    expect($subSpecialty->jobs)->toHaveCount(2);
});
