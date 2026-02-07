<?php

namespace App\Repositories;

use App\Models\Specialty;
use App\Models\SubSpecialty;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SpecialtyRepository
{
    /**
     * Get all specialties with their sub-specialties.
     * Simple query - kept in repository.
     * Cached for 1 hour as this is static data that rarely changes.
     *
     * @return Collection
     */
    public function getAllWithSubSpecialties(): Collection
    {
        return Cache::remember(
            'specialties:all:with_sub_specialties',
            now()->addHour(),
            function () {
                return Specialty::with('subSpecialties')->get();
            }
        );
    }

    /**
     * Get sub-specialties for a given specialty.
     * Simple query - kept in repository.
     * Cached for 1 hour as this is static data that rarely changes.
     *
     * @param  int  $specialtyId
     * @return Collection
     */
    public function getSubSpecialtiesBySpecialty(int $specialtyId): Collection
    {
        return Cache::remember(
            "specialties:{$specialtyId}:sub_specialties",
            now()->addHour(),
            function () use ($specialtyId) {
                return SubSpecialty::where('specialty_id', $specialtyId)->get();
            }
        );
    }

    /**
     * Clear all specialty caches.
     * Call this when specialties or sub-specialties are updated.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('specialties:all:with_sub_specialties');
        // Clear individual specialty caches (pattern-based clearing would require Redis SCAN)
        // For now, we'll rely on TTL expiration
    }
}
