<?php

namespace App\Application\UseCases\Ponencia;

use App\Domain\Repositories\PonenciaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPonenciasPaginatedUseCase {
    private PonenciaRepository $ponenciaRepository;

    public function __construct(PonenciaRepository $ponenciaRepository){
        $this->ponenciaRepository = $ponenciaRepository;
    }

    public function execute(int $page = 1, int $perPage = 10): LengthAwarePaginator {
        return $this->ponenciaRepository->getPonenciasPaginated($page, $perPage);
    }
}