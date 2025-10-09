<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FilialResource extends JsonResource
{
    public function toArray($request): array
    {
        if (!$this->resource) {
            return [
                'id'        => null,
                'nombre'    => null,
                'direccion' => null,
                'telefono'  => null,
                'email'     => null,
                'foto'      => null,
            ];
        }

        $filial = $this->resource;

        return [
            'id'        => $filial->getId(),
            'nombre'    => $filial->getNombre(),
            'direccion' => $filial->getDireccion(),
            'telefono'  => $filial->getTelefono(),
            'email'     => $filial->getEmail(),
            'foto'      => $filial->getFoto() ? asset(Storage::url($filial->getFoto())) : null,
        ];
    }
}