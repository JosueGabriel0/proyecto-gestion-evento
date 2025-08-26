<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $role = $this->resource;

        return [
            'id' => $role->getId(),
            'nombre' => $role->getNombre(),
        ];
    }
}