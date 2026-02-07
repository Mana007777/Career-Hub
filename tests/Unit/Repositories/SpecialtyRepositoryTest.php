<?php

use App\Repositories\SpecialtyRepository;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('specialty repository can get all specialties with sub specialties', function () {
    $specialty = Specialty::factory()->create();
    SubSpecialty::factory()->count(2)->create(['specialty_id' => $specialty->id]);
    
    $repository = new SpecialtyRepository();
    $specialties = $repository->getAllWithSubSpecialties();
    
    expect($specialties)->toHaveCount(1)
        ->and($specialties->first()->subSpecialties)->toHaveCount(2);
});

test('specialty repository can get sub specialties by specialty', function () {
    $specialty = Specialty::factory()->create();
    SubSpecialty::factory()->count(3)->create(['specialty_id' => $specialty->id]);
    
    $repository = new SpecialtyRepository();
    $subSpecialties = $repository->getSubSpecialtiesBySpecialty($specialty->id);
    
    expect($subSpecialties)->toHaveCount(3);
});

test('specialty repository can clear cache', function () {
    $repository = new SpecialtyRepository();
    
    // Should not throw any exceptions
    $repository->clearCache();
    
    expect(true)->toBeTrue();
});
