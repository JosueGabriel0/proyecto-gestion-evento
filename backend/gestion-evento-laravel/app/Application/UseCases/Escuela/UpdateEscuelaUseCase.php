<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;

class UpdateEscuelaUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {
    }

    public function execute(int $id, string $nombre, string $codigo, int $facultad_id, ?string $foto = null): Escuela {
       $escuelaActualizada = new Escuela(
        id: $id,
        nombre: $nombre,
        codigo: $codigo,
        facultad_id: $facultad_id,
        foto: $foto,
       );

        return $this->escuelaRepository->update($id, $escuelaActualizada);
    }
}