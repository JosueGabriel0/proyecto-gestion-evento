<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Repositories\EscuelaRepository;

class SearchEscuelaUseCase {
    private EscuelaRepository $escuelaRepository;

    public function __construct(EscuelaRepository $escuelaRepository)
    {
        $this->escuelaRepository = $escuelaRepository;
    }

    public function execute(string $term, int $perPage = 10){
        return $this->escuelaRepository->searchEscuela($term, $perPage);
    }
}