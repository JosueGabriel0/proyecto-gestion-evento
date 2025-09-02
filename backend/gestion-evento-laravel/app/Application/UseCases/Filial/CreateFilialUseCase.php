<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Entities\Filial;
use App\Domain\Repositories\FilialRepository;

class CreateFilialUseCase {
    public function __construct(private FilialRepository $filialRepository)
    {
    }

    public function execute(string $nombre, ?string $direccion, ?string $telefono, ?string $email, ?string $foto): Filial{
        $nuevaFilial = new Filial(0, $nombre, $direccion, $telefono, $email, $foto);
        return $this -> filialRepository -> create($nuevaFilial); 
    }
}