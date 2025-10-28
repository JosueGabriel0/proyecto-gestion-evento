<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Entities\User as DomainUser;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        // Si el recurso es un Eloquent Model, lo convertimos a Entidad de Dominio
        $user = $this->resource instanceof DomainUser
            ? $this->resource
            : ($this->resource->toDomain() ?? null);

        if (!$user) {
            return [];
        }

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'escuela_id' => $user->getEscuelaId(),
            'role' => $user->getRoleName(),

            'persona' => [
                'nombres' => $user->getPersonaNombres(),
                'apellidos' => $user->getPersonaApellidos(),
                'tipo_documento' => $user->getPersonaTipoDocumento(),
                'numero_documento' => $user->getPersonaNumeroDocumento(),
                'telefono' => $user->getPersonaTelefono(),
                'direccion' => $user->getPersonaDireccion(),
                'pais' => $user->getPersonaPais(),
                'religion' => $user->getPersonaReligion(),
                'correo_electronico' => $user->getPersonaCorreoElectronico(),
                'correo_institucional' => $user->getPersonaCorreoInstitucional(),
                'foto_perfil' => $user->getPersonaFotoPerfil() ? asset(Storage::url($user->getPersonaFotoPerfil())) : null,
                'fecha_nacimiento' => $user->getPersonaFechaNacimiento(),
            ],

            'alumno' => $user->getAlumno() ? [
                'codigo_universitario' => $user->getAlumnoCodigoUniversitario(),
            ] : null,

            'jurado' => $user->getJurado() ? [
                'especialidad' => $user->getJuradoEspecialidad(),
            ] : null,

            'ponente' => $user->getPonente() ? [
                'biografia' => $user->getPonenteBiografia(),
            ] : null,

            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ];
    }
}