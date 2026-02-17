<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Entities\Ponencia;
use App\Domain\Repositories\PonenciaRepository;

class UpdatePonenciaUseCase {
    public function __construct(private PonenciaRepository $ponenciaRepository)
    {
    }

     public function execute(int $id, Ponencia $ponencia): Ponencia{
        return $this -> ponenciaRepository -> update($id, $ponencia); 
    }
}