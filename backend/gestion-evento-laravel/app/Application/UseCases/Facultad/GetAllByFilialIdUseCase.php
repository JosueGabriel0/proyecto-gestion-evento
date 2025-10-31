<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;

class GetAllByFilialIdUseCase
{
    public function __construct(
        private FacultadRepository $facultadRepository
    ) {}

    public function execute(int $id): array
    {
        return $this->facultadRepository->getAllByFilialId($id);
    }
}