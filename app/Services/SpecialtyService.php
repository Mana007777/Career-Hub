<?php

namespace App\Services;

use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Database\Eloquent\Collection;

class SpecialtyService
{
    /**
     * Get all specialties with their sub-specialties.
     *
     * @return Collection
     */
    public function getAllSpecialtiesWithSubSpecialties(): Collection
    {
        return Specialty::with('subSpecialties')->get();
    }

    /**
     * Get sub-specialties for a given specialty.
     *
     * @param  int  $specialtyId
     * @return Collection
     */
    public function getSubSpecialtiesBySpecialty(int $specialtyId): Collection
    {
        return SubSpecialty::where('specialty_id', $specialtyId)->get();
    }
}
