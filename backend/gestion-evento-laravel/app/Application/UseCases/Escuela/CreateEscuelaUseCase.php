<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;

class CreateEscuelaUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {        
    }

    public function execute(string $nombre, string $codigo, int $facultadId, ?string $foto = null): Escuela{
        $nuevaEscuela = new Escuela(
            id : null,
            nombre: $nombre,
            codigo: $codigo,
            facultadId: $facultadId,
            foto: $foto,
        );

        return $this->escuelaRepository->save($nuevaEscuela);
    }
}