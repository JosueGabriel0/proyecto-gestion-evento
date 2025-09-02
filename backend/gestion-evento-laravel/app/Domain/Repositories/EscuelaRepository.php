<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Escuela;

interface EscuelaRepository {
    public function findById(int $id): ?Escuela;
    public function findAll(): array;
    public function save(Escuela $escuela): Escuela;
    public function update(int $id, Escuela $escuela): Escuela;
    public function delete(int $id): void;
}