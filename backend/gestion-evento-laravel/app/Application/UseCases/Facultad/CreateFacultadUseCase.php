<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Entities\Facultad;
use App\Domain\Repositories\FacultadRepository;

class CreateFacultadUseCase
{
    public function __construct(private FacultadRepository $repository) {}

    public function execute(string $nombre, string $codigo, int $filialId, ?string $foto = null): Facultad
    {
        $facultad = new Facultad(
            id: null,
            nombre: $nombre,
            codigo: $codigo,
            filialId: $filialId,
            foto: $foto
        );

        return $this->repository->save($facultad);
    }
}