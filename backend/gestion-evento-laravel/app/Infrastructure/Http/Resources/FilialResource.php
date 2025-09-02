<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Filial",
 *     type="object",
 *     title="Filial",
 *     description="Entidad Filial",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Filial Lima"),
 *     @OA\Property(property="direccion", type="string", nullable=true, example="Av. Siempre Viva 123"),
 *     @OA\Property(property="telefono", type="string", nullable=true, example="987654321"),
 *     @OA\Property(property="email", type="string", nullable=true, example="filial@upeu.edu.pe")
 * )
 */
class FilialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->getId(),
            'nombre'    => $this->getNombre(),
            'direccion' => $this->getDireccion(),
            'telefono'  => $this->getTelefono(),
            'email'     => $this->getEmail(),
            'foto' => $this->getFoto() ? Storage::url($this->getFoto()) : null,
        ];
    }
}