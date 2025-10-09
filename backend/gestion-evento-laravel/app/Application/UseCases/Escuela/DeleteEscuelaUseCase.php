<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Repositories\EscuelaRepository;

class DeleteEscuelaUseCase {
    public function __construct(private EscuelaRepository $escuelaRepository)
    {
    }

    public function execute(int $id): void{
        $this->escuelaRepository->delete($id);
    }
}