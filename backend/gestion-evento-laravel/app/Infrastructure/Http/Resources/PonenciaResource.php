<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class PonenciaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->getId(),
            'nombre'               => $this->getNombre(),
            'eventoId'             => $this->getEventoId(),
            'categoriaId'          => $this->getCategoriaId(),
            'ponente'              => $this->getPonente(),
            'institucion'          => $this->getInstitucion(),
            'archivoPresentacion'  => $this->getArchivoPresentacion()
                                            ? asset(Storage::url($this->getArchivoPresentacion()))
                                            : null,
            'foto'                 => $this->getFoto()
                                            ? asset(Storage::url($this->getFoto()))
                                            : null,
            'codigoQr'             => $this->getCodigoQr(),
        ];
    }
}