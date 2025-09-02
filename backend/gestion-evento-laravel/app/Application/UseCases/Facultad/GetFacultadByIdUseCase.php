<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;
use App\Domain\Entities\Facultad;

class GetFacultadByIdUseCase
{
    public function __construct(
        private FacultadRepository $facultadRepository
    ) {}

    public function execute(int $id): ?Facultad
    {
        return $this->facultadRepository->findById($id);
    }
}