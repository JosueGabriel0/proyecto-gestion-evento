<?php

namespace App\Domain\Entities;

class Escuela
{
    private ?int $id;
    private string $nombre;
    private string $codigo;
    private int $facultadId;
    private ?string $foto;

    public function __construct(
        ?int $id,
        string $nombre,
        string $codigo,
        int $facultadId,
        ?string $foto = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->facultadId = $facultadId;
        $this->foto = $foto;
    }

    // 🔹 Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getFacultadId(): int
    {
        return $this->facultadId;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    // 🔹 Setters
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setFacultadId(int $facultad_id): void
    {
        $this->facultadId = $facultad_id;
    }

    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }
}
