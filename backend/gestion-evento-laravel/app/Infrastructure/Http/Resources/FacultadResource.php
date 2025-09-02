<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Facultad",
 *     type="object",
 *     title="Facultad",
 *     description="Representación de una facultad",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Facultad de Ingeniería"),
 *     @OA\Property(property="codigo", type="string", example="FI001"),
 *     @OA\Property(property="foto", type="string", nullable=true, example="images/facultades/foto.png"),
 *     @OA\Property(property="filialId", type="integer", example=2),
 * )
 */
class FacultadResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->getId(),
            'nombre'    => $this->getNombre(),
            'codigo'    => $this->getCodigo(),
            'foto'      => $this->getFoto(),
            'filialId'  => $this->getFilialId(),
        ];
    }
}