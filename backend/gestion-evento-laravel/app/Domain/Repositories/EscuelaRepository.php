<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Escuela;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EscuelaRepository {
    public function findById(int $id): ?Escuela;
    public function findAll(): array;
    public function getAllEscuelasByFacultadId(int $id): array;
    public function save(Escuela $escuela): Escuela;
    public function update(int $id, Escuela $escuela): Escuela;
    public function delete(int $id): void;
    public function getEscuelasPaginated(int $page = 1, int $perPage = 10): LengthAwarePaginator;
    public function searchEscuela(string $term, int $perPage = 10): LengthAwarePaginator;
}