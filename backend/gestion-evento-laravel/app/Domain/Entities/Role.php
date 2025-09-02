<?php

namespace App\Domain\Entities;

class Role
{
    private int $id;
    private string $nombre;
    private ?string $foto;

    public function __construct(int $id, string $nombre, ?string $foto = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->foto = $foto;
    }

    // Getters y setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }
}
