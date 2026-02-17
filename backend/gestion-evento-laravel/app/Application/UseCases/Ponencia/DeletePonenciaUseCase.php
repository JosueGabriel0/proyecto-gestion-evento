<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Repositories\PonenciaRepository;

class DeletePonenciaUseCase{
    public function __construct(private PonenciaRepository $ponenciaRepository)
    {
    }

    public function execute(int $id): void{
        $this->ponenciaRepository->delete($id); 
    }
}