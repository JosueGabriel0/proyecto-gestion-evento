<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFullResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            // Rol del usuario
            'role' => $this->role ? [
                'id' => $this->role->id,
                'name' => $this->role->nombre,
            ] : null,

            // Información de la persona relacionada
            'persona' => $this->persona ? [
                'nombres' => $this->persona->nombres,
                'apellidos' => $this->persona->apellidos,
                'tipoDocumento' => $this->persona->tipoDocumento,
                'numeroDocumento' => $this->persona->numeroDocumento,
                'telefono' => $this->persona->telefono,
                'direccion' => $this->persona->direccion,
                'fechaNacimiento' => $this->persona->fechaNacimiento,
                'fotoPerfil' => $this->persona->fotoPerfil, // <-- nuevo campo
            ] : null,


            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
