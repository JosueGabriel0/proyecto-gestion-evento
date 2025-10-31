<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Repositories\EscuelaRepository;

class GetAllEscuelasByFacultadIdUseCase
{
    public function __construct(
        private EscuelaRepository $escuelaRepository
    ) {}

    public function execute(int $id): array
    {
        return $this->escuelaRepository->getAllEscuelasByFacultadId($id);
    }
}