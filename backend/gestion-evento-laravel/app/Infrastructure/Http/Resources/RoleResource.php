<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        if (!$this->resource) {
            return [
                'id' => null,
                'nombre' => null,
                'foto' => null,
            ];
        }

        $role = $this->resource;

        return [
            'id' => $role->getId(),
            'nombre' => $role->getNombre(),
            'foto' => $role->getFoto() ? asset(Storage::url($role->getFoto())) : null,
        ];
    }
}
