<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Repositories\FilialRepository;

class AllFilialesUseCase {
    public function __construct(private FilialRepository $filialRepository)
    {
    }

    public function execute(): array{
        return $this->filialRepository->all();
    }
}