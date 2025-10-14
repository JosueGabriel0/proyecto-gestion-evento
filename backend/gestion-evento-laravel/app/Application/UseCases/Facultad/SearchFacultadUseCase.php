<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;

class SearchFacultadUseCase {
    private FacultadRepository $facultadRepository;

    public function __construct(FacultadRepository $facultadRepository)
    {
        $this->facultadRepository = $facultadRepository;
    }

    public function execute(string $term, int $perPage = 10){
        return $this->facultadRepository->searchFacultad($term, $perPage);
    }
}