<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Facultad;

interface FacultadRepository
{
    public function findById(int $id): ?Facultad;

    /**
     * @return Facultad[]
     */
    public function getAll(): array;

    public function save(Facultad $facultad): Facultad;

    public function update(int $id, Facultad $filial): Facultad;

    public function delete(int $id): void;
}