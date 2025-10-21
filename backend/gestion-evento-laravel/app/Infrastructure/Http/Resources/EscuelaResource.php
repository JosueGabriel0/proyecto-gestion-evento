<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class EscuelaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->getId(),
            'nombre'      => $this->getNombre(),
            'codigo'      => $this->getCodigo(),
            'facultadId' => $this->getFacultadId(),
            'foto' => $this->getFoto() ? asset(Storage::url($this->getFoto())) : null,
        ];
    }
}