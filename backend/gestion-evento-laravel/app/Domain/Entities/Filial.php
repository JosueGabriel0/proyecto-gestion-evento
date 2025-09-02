<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\Eloquent\Models\FacultadModel;

class Filial
{
    private int $id;
    private string $nombre;
    private ?string $direccion;
    private ?string $telefono;
    private ?string $email;
    private ?string $foto;

    /** @var Facultad[] */
    private array $facultades = [];

    public function __construct(
        int $id,
        string $nombre,
        ?string $direccion = null,
        ?string $telefono = null,
        ?string $email = null,
        ?string $foto = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->foto = $foto;
    }

    // ✅ Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

     public function getFoto(): ?string
    {
        return $this->foto;
    }

    // ✅ Relación con Facultades
    public function getFacultades(): array
    {
        return $this->facultades;
    }

    public function addFacultad(FacultadModel $facultad): void
    {
        $this->facultades[] = $facultad;
    }
}