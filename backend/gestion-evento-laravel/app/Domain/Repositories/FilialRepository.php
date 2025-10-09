<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Filial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FilialRepository {
    public function all(): array;
    public function find(int $id): ?Filial;
    public function create(Filial $filial): Filial;
    public function update(int $id, Filial $filial): Filial;
    public function delete(int $id): void;
    public function getFilialesPaginated(int $page = 1,int $perPage = 10): LengthAwarePaginator;
    public function searchFiliales(string $term, int $perPage = 10): LengthAwarePaginator;
}