<?php

namespace App\Domain\Entities;

class Ponente
{
    private ?int $id;
    private ?string $biografia;
    private ?int $userId;
    private ?int $ponenciaId;

    /**
     * Constructor
     */
    public function __construct(?int $id, ?string $biografia, ?int $userId, ?int $ponenciaId)
    {
        $this->id = $id;
        $this->biografia = $biografia;
        $this->userId = $userId;
        $this->ponenciaId = $ponenciaId;
    }

    // 🔹 Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiografia(): ?string
    {
        return $this->biografia;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getPonenciaId(): ?int
    {
        return $this->ponenciaId;
    }

    // 🔹 Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    
    public function setBiografia(?string $biografia): void
    {
        $this->biografia = $biografia;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setPonenciaId(?int $ponenciaId): void
    {
        $this->ponenciaId = $ponenciaId;
    }

    // 🔹 Relaciones opcionales (inyectadas desde repositorios)
    private ?object $user = null;
    private ?object $ponencia = null;

    public function setUser(object $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setPonencia(object $ponencia): void
    {
        $this->ponencia = $ponencia;
    }

    public function getPonencia(): ?object
    {
        return $this->ponencia;
    }
}