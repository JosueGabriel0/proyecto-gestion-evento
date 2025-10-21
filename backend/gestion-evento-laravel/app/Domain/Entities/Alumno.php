<?php

namespace App\Domain\Entities;

class Alumno
{
    private ?int $id;
    private ?int $userId;
    private ?string $codigo_universitario;

    /**
     * Constructor
     */
    public function __construct(?int $id, ?int $userId, ?string $codigo_universitario = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->codigo_universitario = $codigo_universitario;
    }

    // 🔹 Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCodigoUniversitario(): ?string
    {
        return $this->codigo_universitario;
    }

    // 🔹 Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setCodigoUniversitario(?string $codigo_universitario): void
    {
        $this->codigo_universitario = $codigo_universitario;
    }
}