<?php

namespace App\Application\UseCases\User;

class CreateUserWithPersonaCommand
{
    public function __construct(
        // User
        private readonly string $email,
        private readonly string $rawPassword,
        private readonly int $escuelaId,
        private readonly ?int $roleId,

        // Persona
        private readonly string $nombres,
        private readonly string $apellidos,
        private readonly string $tipoDocumento,
        private readonly string $numeroDocumento,
        private readonly string $telefono,
        private readonly string $direccion,
        private readonly string $correoElectronico,
        private readonly ?string $fotoPerfil,
        private readonly string $fechaNacimientoYmd, // "YYYY-MM-DD"

        // Ponente
        private readonly ?string $biografia = null,
        private readonly ?int $userId = null,
        private readonly ?int $ponenciaId = null,

        // Alumno
        private readonly ?string $codigoUniversitario = null,
        private readonly ?string $carrera = null,
        private readonly ?string $ciclo = null,

        // Jurado
        private readonly ?string $especialidad = null,
    ) {}

    // ==== Getters ====

    // User
    public function getEmail(): string { return $this->email; }
    public function getRawPassword(): string { return $this->rawPassword; }
    public function getEscuelaId(): int { return $this->escuelaId; }
    public function getRoleId(): ?int { return $this->roleId; }

    // Persona
    public function getNombres(): string { return $this->nombres; }
    public function getApellidos(): string { return $this->apellidos; }
    public function getTipoDocumento(): string { return $this->tipoDocumento; }
    public function getNumeroDocumento(): string { return $this->numeroDocumento; }
    public function getTelefono(): string { return $this->telefono; }
    public function getDireccion(): string { return $this->direccion; }
    public function getCorreoElectronico(): string { return $this->correoElectronico; }
    public function getFotoPerfil(): ?string { return $this->fotoPerfil; }
    public function getFechaNacimientoYmd(): string { return $this->fechaNacimientoYmd; }

    // Ponente
    public function getBiografia(): ?string { return $this->biografia; }
    public function getUserId(): ?int { return $this->userId; }
    public function getPonenciaId(): ?int { return $this->ponenciaId; }

    // Alumno
    public function getCodigoUniversitario(): ?string { return $this->codigoUniversitario; }
    public function getCarrera(): ?string { return $this->carrera; }
    public function getCiclo(): ?string { return $this->ciclo; }

    // Jurado
    public function getEspecialidad(): ?string { return $this->especialidad; }
}