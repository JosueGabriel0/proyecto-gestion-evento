<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Ponencia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PonenciaRepository {
    public function all(): array;
    public function find(int $id): ?Ponencia;
    public function create(Ponencia $ponencia): Ponencia;
    public function update(int $id, Ponencia $ponencia): Ponencia;
    public function delete(int $id): void;
    public function getPonenciasPaginated(int $page = 1,int $perPage = 10): LengthAwarePaginator;
    public function searchPonencias(string $term, int $perPage = 10): LengthAwarePaginator;
}