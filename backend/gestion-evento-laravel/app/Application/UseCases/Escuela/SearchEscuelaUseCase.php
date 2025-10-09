<?php

namespace App\Application\UseCases\Escuela; // ❌ Estaba en Filial, debe ser Escuela

use App\Domain\Repositories\EscuelaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchEscuelaUseCase {
    private EscuelaRepository $escuelaRepository;

    public function __construct(EscuelaRepository $escuelaRepository)
    {
        $this->escuelaRepository = $escuelaRepository;
    }

    public function execute(string $term, int $perPage = 10): LengthAwarePaginator {
        return $this->escuelaRepository->searchEscuela($term, $perPage);
    }
}