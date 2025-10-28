<?php

namespace App\Domain\Entities;

use DateTime;

class User
{
    private ?int $id;
    private string $email;
    private string $password;
    private int $escuelaId;
    private ?int $roleId;
    private ?DateTime $emailVerifiedAt;
    private ?string $rememberToken;

    // Relaciones (composición / agregación)
    private ?Persona $persona;
    private ?Role $role;
    private ?Alumno $alumno;
    private ?Jurado $jurado;
    private ?Ponente $ponente;

    public function __construct(
        ?int $id,
        string $email,
        string $password,
        int $escuelaId,
        ?int $roleId = null,
        ?DateTime $emailVerifiedAt = null,
        ?string $rememberToken = null,
        ?Persona $persona = null,
        ?Role $role = null,
        ?Alumno $alumno = null,
        ?Jurado $jurado = null,
        ?Ponente $ponente = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->escuelaId = $escuelaId;
        $this->roleId = $roleId;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->rememberToken = $rememberToken;
        $this->persona = $persona;
        $this->role = $role;
        $this->alumno = $alumno;
        $this->jurado = $jurado;
        $this->ponente = $ponente;
    }

    // ======================
    // 🔹 Getters y Setters
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $hash): void
    {
        $this->password = $hash;
    }

    public function getEscuelaId(): int
    {
        return $this->escuelaId;
    }

    public function setEscuelaId(int $id): void
    {
        $this->escuelaId = $id;
    }

    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function verifyEmail(DateTime $at): void
    {
        $this->emailVerifiedAt = $at;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $token): void
    {
        $this->rememberToken = $token;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): void
    {
        $this->roleId = $roleId;
    }

    // ======================
    // 🔹 Relaciones
    // ======================

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): void
    {
        $this->persona = $persona;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): void
    {
        $this->alumno = $alumno;
    }

    public function getJurado(): ?Jurado
    {
        return $this->jurado;
    }

    public function setJurado(?Jurado $jurado): void
    {
        $this->jurado = $jurado;
    }

    public function getPonente(): ?Ponente
    {
        return $this->ponente;
    }

    public function setPonente(?Ponente $ponente): void
    {
        $this->ponente = $ponente;
    }

    // ======================
    // 🔹 Métodos de ayuda
    // ======================

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->getNombre() === $roleName;
    }

    public function isVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'escuela_id' => $this->escuelaId,
            'role_id' => $this->roleId,
            'email_verified_at' => $this->emailVerifiedAt?->format('Y-m-d H:i:s'),
            'persona' => $this->persona ? [
                'nombres' => $this->persona->getNombres(),
                'apellidos' => $this->persona->getApellidos(),
                'correo' => $this->persona->getCorreoElectronico(),
            ] : null,
            'role' => $this->role ? $this->role->getNombre() : null,
            'alumno' => $this->alumno ? [
                'codigo_universitario' => $this->alumno->getCodigoUniversitario(),
            ] : null,
            'jurado' => $this->jurado ? [
                'especialidad' => $this->jurado->getEspecialidad(),
            ] : null,
            'ponente' => $this->ponente ? [
                'biografia' => $this->ponente->getBiografia(),
            ] : null,
        ];
    }

    // ======================
    // 🔹 Getters derivados (para el Resource)
    // ======================

    public function getRoleName(): ?string
    {
        return $this->role?->getNombre();
    }

    // Persona
    public function getPersonaNombres(): ?string
    {
        return $this->persona?->getNombres();
    }

    public function getPersonaApellidos(): ?string
    {
        return $this->persona?->getApellidos();
    }

    public function getPersonaTipoDocumento(): ?string
    {
        return $this->persona?->getTipoDocumento();
    }

    public function getPersonaNumeroDocumento(): ?string
    {
        return $this->persona?->getNumeroDocumento();
    }

    public function getPersonaTelefono(): ?string
    {
        return $this->persona?->getTelefono();
    }

    public function getPersonaDireccion(): ?string
    {
        return $this->persona?->getDireccion();
    }

    public function getPersonaPais(): ?string
    {
        return $this->persona?->getPais();
    }

    public function getPersonaReligion(): ?string
    {
        return $this->persona?->getReligion();
    }

    public function getPersonaCorreoElectronico(): ?string
    {
        return $this->persona?->getCorreoElectronico();
    }

    public function getPersonaCorreoInstitucional(): ?string
    {
        return $this->persona?->getCorreoInstitucional();
    }

    public function getPersonaFotoPerfil(): ?string
    {
        return $this->persona?->getFotoPerfil();
    }

    public function getPersonaFechaNacimiento(): ?string
    {
        return $this->persona?->getFechaNacimiento()?->format('Y-m-d');
    }

    // Relaciones por rol
    public function getAlumnoCodigoUniversitario(): ?string
    {
        return $this->alumno?->getCodigoUniversitario();
    }

    public function getJuradoEspecialidad(): ?string
    {
        return $this->jurado?->getEspecialidad();
    }

    public function getPonenteBiografia(): ?string
    {
        return $this->ponente?->getBiografia();
    }

    // ======================
    // 🔹 Timestamps (opcional si los necesitas en el Resource)
    // ======================

    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    public function getCreatedAt(): ?string
    {
        return $this->createdAt?->format('Y-m-d H:i:s');
    }

    public function setCreatedAt(?DateTime $date): void
    {
        $this->createdAt = $date;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt?->format('Y-m-d H:i:s');
    }

    public function setUpdatedAt(?DateTime $date): void
    {
        $this->updatedAt = $date;
    }
}
