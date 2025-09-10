<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;

class GetEscuelaByIdUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {
    }

    public function execute(int $id): Escuela {
        $escuelaEncontrada = $this->escuelaRepository->findById($id);
        return $escuelaEncontrada;
    }
}