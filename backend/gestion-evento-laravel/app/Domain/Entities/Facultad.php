<?php

namespace App\Domain\Entities;

class Facultad
{
    private ?int $id;
    private string $nombre;
    private string $codigo;
    private ?string $foto;
    private int $filialId; // relación con Filial

    public function __construct(
        ?int $id,
        string $nombre,
        string $codigo,
        int $filialId,
        ?string $foto = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->filialId = $filialId;
        $this->foto = $foto;
    }

    // ✅ Getters
    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCodigo(): string { return $this->codigo; }
    public function getFoto(): ?string { return $this->foto; }
    public function getFilialId(): int { return $this->filialId; }

    // ✅ Setters
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setCodigo(string $codigo): void { $this->codigo = $codigo; }
    public function setFoto(?string $foto): void { $this->foto = $foto; }
    public function setFilialId(int $filialId): void { $this->filialId = $filialId; }
}