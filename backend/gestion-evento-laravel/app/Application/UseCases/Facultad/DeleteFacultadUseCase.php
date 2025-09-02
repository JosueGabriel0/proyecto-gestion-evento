<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;

class DeleteFacultadUseCase
{
    public function __construct(
        private FacultadRepository $facultadRepository
    ) {}

    public function execute(int $id): void
    {
        $this->facultadRepository->delete($id);
    }
}