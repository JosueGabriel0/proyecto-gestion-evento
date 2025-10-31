<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Facultad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FacultadRepository
{
    public function findById(int $id): ?Facultad;

    /**
     * @return Facultad[]
     */
    public function getAll(): array;

    public function getAllByFilialId(int $id): array;

    public function save(Facultad $facultad): Facultad;

    public function update(int $id, Facultad $filial): Facultad;

    public function delete(int $id): void;

    public function getFacultadesPaginated(int $page = 1, int $perPage = 10): LengthAwarePaginator;
    
    public function searchFacultad(string $term, int $perPage = 10): LengthAwarePaginator;
}
