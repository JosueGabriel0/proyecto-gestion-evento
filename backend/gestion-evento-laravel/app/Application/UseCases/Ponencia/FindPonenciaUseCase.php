<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Entities\Ponencia;
use App\Domain\Repositories\PonenciaRepository;

class FindPonenciaUseCase{
    public function __construct(private PonenciaRepository $ponenciaRepository){}

    public function execute(int $id): ?Ponencia{
        return $this->ponenciaRepository->find($id);
    }
}