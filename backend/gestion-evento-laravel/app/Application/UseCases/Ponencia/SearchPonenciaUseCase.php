<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Repositories\PonenciaRepository;

class SearchPonenciaUseCase {
    private PonenciaRepository $ponenciaRepository;

    public function __construct(PonenciaRepository $ponenciaRepository)
    {
        $this->ponenciaRepository = $ponenciaRepository;
    }

    public function execute(string $term, int $perPage = 10){
        return $this->ponenciaRepository->searchPonencias($term, $perPage);
    }
}