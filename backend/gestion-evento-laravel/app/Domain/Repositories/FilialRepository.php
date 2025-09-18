<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Filial;

interface FilialRepository {
    public function all(): array;
    public function find(int $id): ?Filial;
    public function create(Filial $filial): Filial;
    public function update(int $id, Filial $filial): Filial;
    public function delete(int $id): void;
}