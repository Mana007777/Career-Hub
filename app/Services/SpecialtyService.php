<?php

namespace App\Services;

use App\Repositories\SpecialtyRepository;
use Illuminate\Database\Eloquent\Collection;

class SpecialtyService
{
    protected SpecialtyRepository $repository;

    public function __construct(SpecialtyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all specialties with their sub-specialties.
     *
     * @return Collection
     */
    public function getAllSpecialtiesWithSubSpecialties(): Collection
    {
        return $this->repository->getAllWithSubSpecialties();
    }

    /**
     * Get sub-specialties for a given specialty.
     *
     * @param  int  $specialtyId
     * @return Collection
     */
    public function getSubSpecialtiesBySpecialty(int $specialtyId): Collection
    {
        return $this->repository->getSubSpecialtiesBySpecialty($specialtyId);
    }
}
