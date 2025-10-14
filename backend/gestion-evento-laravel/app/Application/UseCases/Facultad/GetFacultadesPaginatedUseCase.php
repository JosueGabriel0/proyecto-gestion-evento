<?php

namespace App\Application\UseCases\Facultad;

use App\Domain\Repositories\FacultadRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetFacultadesPaginatedUseCase {
    private FacultadRepository $facultadRepository;

    public function __construct(FacultadRepository $facultadRepository){
        $this->facultadRepository = $facultadRepository;
    }

    public function execute(int $page = 1, int $perPage = 10): LengthAwarePaginator {
        return $this->facultadRepository->getFacultadesPaginated($page, $perPage);
    }
}