<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Entities\Ponencia;
use App\Domain\Repositories\PonenciaRepository;

class CreatePonenciaUseCase {
    public function __construct(private PonenciaRepository $ponenciaRepository)
    {
    }

    public function execute(Ponencia $ponencia): Ponencia{
        return $this -> ponenciaRepository -> create($ponencia);
    }
}