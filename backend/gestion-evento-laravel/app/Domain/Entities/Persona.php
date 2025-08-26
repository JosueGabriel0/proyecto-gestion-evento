<?php

namespace App\Domain\Entities;

use App\Domain\Entities\Role;
use App\Domain\Entities\User;

class Persona
{
    private int $id;
    private string $nombres;
    private string $apellidos;
    private string $tipoDocumento;
    private string $numeroDocumento;
    private ?string $telefono = null;
    private ?string $direccion = null;
    private ?string $correoElectronico = null;
    private ?string $fotoPerfil = null;
    private ?\DateTime $fechaNacimiento = null;

    private ?User $user = null;
    private ?Role $role = null;

    public function __construct(
        int $id,
        string $nombres,
        string $apellidos,
        string $tipoDocumento,
        string $numeroDocumento
    ) {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->tipoDocumento = $tipoDocumento;
        $this->numeroDocumento = $numeroDocumento;
    }

    // Getters y setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNombres(): string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    public function getNumeroDocumento(): string
    {
        return $this->numeroDocumento;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getCorreoElectronico(): ?string
    {
        return $this->correoElectronico;
    }

    public function setCorreoElectronico(?string $correoElectronico): void
    {
        $this->correoElectronico = $correoElectronico;
    }

    public function getFotoPerfil(): ?string
    {
        return $this->fotoPerfil;
    }

    public function setFotoPerfil(?string $fotoPerfil): void
    {
        $this->fotoPerfil = $fotoPerfil;
    }

    public function getFechaNacimiento(): ?\DateTime
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTime $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }
}