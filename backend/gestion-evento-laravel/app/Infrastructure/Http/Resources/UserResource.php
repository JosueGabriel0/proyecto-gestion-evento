<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'escuela_id' => $this->getEscuelaId(),
            'role' => $this->getRoleName(),

            'persona' => [
                'nombres' => $this->getPersonaNombres(),
                'apellidos' => $this->getPersonaApellidos(),
                'tipo_documento' => $this->getPersonaTipoDocumento(),
                'numero_documento' => $this->getPersonaNumeroDocumento(),
                'telefono' => $this->getPersonaTelefono(),
                'direccion' => $this->getPersonaDireccion(),
                'correo_electronico' => $this->getPersonaCorreoElectronico(),
                'foto_perfil' => $this->getPersonaFotoPerfil(),
                'fecha_nacimiento' => $this->getPersonaFechaNacimiento(),
            ],

            'alumno' => $this->getAlumno() ? [
                'codigo_universitario' => $this->getAlumnoCodigoUniversitario(),
            ] : null,

            'jurado' => $this->getJurado() ? [
                'especialidad' => $this->getJuradoEspecialidad(),
            ] : null,

            'ponente' => $this->getPonente() ? [
                'biografia' => $this->getPonenteBiografia(),
            ] : null,

            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}