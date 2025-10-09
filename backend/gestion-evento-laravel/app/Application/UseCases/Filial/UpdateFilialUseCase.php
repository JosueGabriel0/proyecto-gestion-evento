<?php

namespace App\Application\UseCases\Filial;

use App\Domain\Entities\Filial;
use App\Domain\Repositories\FilialRepository;

class UpdateFilialUseCase {
    public function __construct(private FilialRepository $filialRepository)
    {
    }

     public function execute(int $id, string $nombre, ?string $direccion, ?string $telefono, ?string $email, ?string $foto): Filial{
        $nuevaFilial = new Filial($id, $nombre, $direccion, $telefono, $email, $foto);
        return $this -> filialRepository -> update($id, $nuevaFilial); 
    }
}