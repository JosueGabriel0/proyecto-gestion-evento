<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        $role = $this->resource;

        return [
            'id' => $role->getId(),
            'nombre' => $role->getNombre(),
            'foto' => $this->getFoto() ? asset(Storage::url($this->getFoto())) : null,
        ];
    }
}
