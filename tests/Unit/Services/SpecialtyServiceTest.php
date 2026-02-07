<?php

use App\Services\SpecialtyService;
use App\Repositories\SpecialtyRepository;
use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('specialty service can get all specialties with sub specialties', function () {
    $specialty = Specialty::factory()->create();
    SubSpecialty::factory()->count(2)->create(['specialty_id' => $specialty->id]);
    
    $service = new SpecialtyService(new SpecialtyRepository());
    $specialties = $service->getAllSpecialtiesWithSubSpecialties();
    
    expect($specialties)->toHaveCount(1)
        ->and($specialties->first()->subSpecialties)->toHaveCount(2);
});

test('specialty service can get sub specialties by specialty', function () {
    $specialty = Specialty::factory()->create();
    SubSpecialty::factory()->count(3)->create(['specialty_id' => $specialty->id]);
    
    $service = new SpecialtyService(new SpecialtyRepository());
    $subSpecialties = $service->getSubSpecialtiesBySpecialty($specialty->id);
    
    expect($subSpecialties)->toHaveCount(3);
});
