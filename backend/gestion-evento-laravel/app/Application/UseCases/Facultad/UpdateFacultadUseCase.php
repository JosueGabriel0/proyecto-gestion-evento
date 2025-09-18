<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Entities\Facultad;
use App\Domain\Repositories\FacultadRepository;

class UpdateFacultadUseCase {
    public function __construct(private FacultadRepository $facultadRepository)
    {
    }

    public function execute(
        int $id,
        string $nombre,
        string $codigo,
        int $filialId,
        ?string $foto
    ): Facultad {
        $nuevaFacultad = new Facultad(
            id: $id,
            nombre: $nombre,
            codigo: $codigo,
            filialId: $filialId,
            foto: $foto
        );

        return $this->facultadRepository->update($id, $nuevaFacultad);
    }
}