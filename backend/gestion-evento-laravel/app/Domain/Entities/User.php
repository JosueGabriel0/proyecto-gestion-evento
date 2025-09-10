<?php

namespace App\Domain\Entities;

use DateTime;

class User
{
    public function __construct(
        private ?int $id,
        private string $email,
        private string $passwordHash,
        private int $escuelaId,
        private ?int $roleId = null,
        private ?DateTime $emailVerifiedAt = null,
        private ?string $rememberToken = null,
        private ?Persona $persona = null, // composición: 1–1
        private ?Role $role = null,        // ✅ agregamos relación Role
        private ?Ponente $ponente = null,
        private ?Alumno $alumno = null,
        private ?Jurado $jurado = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId= $roleId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function setPasswordHash(string $hash): void
    {
        $this->passwordHash = $hash;
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }
    public function setPassword(string $hash): void
    {
        $this->passwordHash = $hash;
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

    public function getRoleId(): ?int { return $this->roleId; }
    public function assignRoleId(?int $roleId): void { $this->roleId = $roleId; }

    public function getRole(): ?Role { return $this->role; }
    public function setRole(?Role $role): void { $this->role = $role; }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }
    public function setPersona(?Persona $persona): void
    {
        $this->persona = $persona;
    }

    public function getPonente(): ?Ponente
    {
        return $this->ponente;
    }
    public function setPonente(?Ponente $ponente): void
    {
        $this->ponente = $ponente;
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
}
