<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Repositories\PonenciaRepository;

class AllPonenciasUseCase {
    public function __construct(private PonenciaRepository $ponenciaRepository)
    {
    }

    public function execute(): array{
        return $this->ponenciaRepository->all();
    }
}