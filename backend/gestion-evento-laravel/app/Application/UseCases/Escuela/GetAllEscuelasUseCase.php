<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Repositories\EscuelaRepository;

class GetAllEscuelasUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {
    }

    public function execute(): array {
        return $this->escuelaRepository->findAll();
    }
}