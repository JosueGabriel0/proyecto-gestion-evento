<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;

class UpdateEscuelaUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {
    }

    public function execute(int $id, string $nombre, string $codigo, int $facultadId, ?string $foto = null): Escuela {
       $escuelaActualizada = new Escuela(
        id: $id,
        nombre: $nombre,
        codigo: $codigo,
        facultadId: $facultadId,
        foto: $foto,
       );

        return $this->escuelaRepository->update($id, $escuelaActualizada);
    }
}