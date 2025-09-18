<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;

class GetAllFacultadesUseCase
{
    public function __construct(
        private FacultadRepository $facultadRepository
    ) {}

    public function execute(): array
    {
        return $this->facultadRepository->getAll();
    }
}