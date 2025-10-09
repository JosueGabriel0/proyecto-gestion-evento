<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Repositories\FilialRepository;

class DeleteFilialUseCase{
    public function __construct(private FilialRepository $filialRepository)
    {
    }

    public function execute(int $id): void{
        $this->filialRepository->delete($id); 
    }
}