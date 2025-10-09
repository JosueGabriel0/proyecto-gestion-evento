<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Repositories\FilialRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetFilialesPaginatedUseCase {
    private FilialRepository $filialRepository;

    public function __construct(FilialRepository $filialRepository){
        $this->filialRepository = $filialRepository;
    }

    public function execute(int $page = 1, int $perPage = 10): LengthAwarePaginator {
        return $this->filialRepository->getFilialesPaginated($page, $perPage);
    }
}