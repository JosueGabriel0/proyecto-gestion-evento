<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Entities\Filial;
use App\Domain\Repositories\FilialRepository;

class FindFilialUseCase{
    public function __construct(private FilialRepository $filialRepository){}

    public function execute(int $id): ?Filial{
        return $this->filialRepository->find($id);
    }
}