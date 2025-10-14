<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class FacultadResource extends JsonResource
{
    private function facultad(): \App\Domain\Entities\Facultad
    {
        return $this->resource;
    }

    public function toArray($request): array
    {
        return [
            'id'        => $this->facultad()->getId(),
            'nombre'    => $this->facultad()->getNombre(),
            'codigo'    => $this->facultad()->getCodigo(),
            'foto'      => $this->facultad()->getFoto()
                ? asset(Storage::url($this->facultad()->getFoto()))
                : null,
            'filialId'  => $this->facultad()->getFilialId(),
        ];
    }
}
