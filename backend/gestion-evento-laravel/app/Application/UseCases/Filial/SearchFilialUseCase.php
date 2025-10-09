<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Repositories\FilialRepository;

class SearchFilialUseCase {
    private FilialRepository $filialRepository;

    public function __construct(FilialRepository $filialRepository)
    {
        $this->filialRepository = $filialRepository;
    }

    public function execute(string $term, int $perPage = 10){
        return $this->filialRepository->searchFiliales($term, $perPage);
    }
}