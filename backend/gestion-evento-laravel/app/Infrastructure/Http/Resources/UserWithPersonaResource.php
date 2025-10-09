<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserWithPersonaResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Domain\Entities\User $user */
        $user = $this->resource;

        return [
            'id'                => $user->getId(),
            'email'             => $user->getEmail(),
            'role_id'           => $user->getRoleId(),
            'escuela_id'        => $user->getEscuelaId(),
            'email_verified_at' => $user->getEmailVerifiedAt()?->format('Y-m-d H:i:s'),
            'created_at'        => null,
            'updated_at'        => null,

            // ─── Role asociado ───────────────────────────────
            'role' => $user->getRole() ? [
                'id'     => $user->getRole()->getId(),
                'nombre' => $user->getRole()->getNombre(),
                'foto'   => $user->getRole()->getFoto(),
            ] : null,

            // ─── Persona asociada ───────────────────────────
            'persona' => $user->getPersona() ? [
                'id'                => $user->getPersona()->getId(),
                'nombres'           => $user->getPersona()->getNombres(),
                'apellidos'         => $user->getPersona()->getApellidos(),
                'tipoDocumento'     => $user->getPersona()->getTipoDocumento(),
                'numeroDocumento'   => $user->getPersona()->getNumeroDocumento(),
                'telefono'          => $user->getPersona()->getTelefono(),
                'direccion'         => $user->getPersona()->getDireccion(),
                'correoElectronico' => $user->getPersona()->getCorreoElectronico(),
                'fotoPerfil'        => $user->getPersona()->getFotoPerfil()
                    ? asset(Storage::url($user->getPersona()->getFotoPerfil()))
                    : null,
                'fechaNacimiento'   => $user->getPersona()->getFechaNacimiento()->format('Y-m-d'),
            ] : null,

            // ─── Alumno (opcional) ──────────────────────────
            'alumno' => $user->getAlumno() ? [
                'id'                 => $user->getAlumno()->getId(),
                'codigoUniversitario'=> $user->getAlumno()->getCodigoUniversitario(),
                'carrera'            => $user->getAlumno()->getCarrera(),
                'ciclo'              => $user->getAlumno()->getCiclo(),
            ] : null,

            // ─── Ponente (opcional) ─────────────────────────
            'ponente' => $user->getPonente() ? [
                'id'         => $user->getPonente()->getId(),
                'biografia'  => $user->getPonente()->getBiografia(),
                'ponencia_id'=> $user->getPonente()->getPonenciaId(),
            ] : null,

            // ─── Jurado (opcional) ──────────────────────────
            'jurado' => $user->getJurado() ? [
                'id'          => $user->getJurado()->getId(),
                'especialidad'=> $user->getJurado()->getEspecialidad(),
            ] : null,
        ];
    }
}