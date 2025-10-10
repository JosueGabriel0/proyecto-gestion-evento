<?php

namespace App\Application\UseCases\Escuela;

use App\Domain\Repositories\EscuelaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetEscuelasPaginatedUseCase {
    private EscuelaRepository $escuelaRepository;

    public function __construct(EscuelaRepository $escuelaRepository){
        $this->escuelaRepository = $escuelaRepository;
    }

    public function execute(int $page = 1, int $perPage = 10): LengthAwarePaginator {
        return $this->escuelaRepository->getEscuelasPaginated($page, $perPage);
    }
}