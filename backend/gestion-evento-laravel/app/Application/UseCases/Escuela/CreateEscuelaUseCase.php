<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;

class CreateEscuelaUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {        
    }

    public function execute(string $nombre, string $codigo, int $facultad_id, ?string $foto = null): Escuela{
        $nuevaEscuela = new Escuela(
            id : null,
            nombre: $nombre,
            codigo: $codigo,
            facultad_id: $facultad_id,
            foto: $foto,
        );

        return $this->escuelaRepository->save($nuevaEscuela);
    }
}