<?php

namespace App\Domain\Entities;

class Ponencia
{
    private ?int $id;
    private string $nombre;
    private int $eventoId;
    private int $categoriaId;
    private string $ponente;
    private ?string $institucion;
    private ?string $archivoPresentacion;
    private ?string $foto;
    private string $codigoQr;

    public function __construct(
        ?int $id,
        string $nombre,
        int $eventoId,
        int $categoriaId,
        string $ponente,
        ?string $institucion = null,
        ?string $archivoPresentacion = null,
        ?string $foto = null,
        string $codigoQr
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->eventoId = $eventoId;
        $this->categoriaId = $categoriaId;
        $this->ponente = $ponente;
        $this->institucion = $institucion;
        $this->archivoPresentacion = $archivoPresentacion;
        $this->foto = $foto;
        $this->codigoQr = $codigoQr;
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

    public function getEventoId(): int
    {
        return $this->eventoId;
    }

    public function getCategoriaId(): int
    {
        return $this->categoriaId;
    }

    public function getPonente(): string
    {
        return $this->ponente;
    }

    public function getInstitucion(): ?string
    {
        return $this->institucion;
    }

    public function getArchivoPresentacion(): ?string
    {
        return $this->archivoPresentacion;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function getCodigoQr(): string
    {
        return $this->codigoQr;
    }

    // 🔹 Setters
    public function setEventoId(int $eventoId): void
    {
        $this->eventoId = $eventoId;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCategoriaId(int $categoriaId): void
    {
        $this->categoriaId = $categoriaId;
    }

    public function setPonente(string $ponente): void
    {
        $this->ponente = $ponente;
    }

    public function setInstitucion(?string $institucion): void
    {
        $this->institucion = $institucion;
    }

    public function setArchivoPresentacion(?string $archivoPresentacion): void
    {
        $this->archivoPresentacion = $archivoPresentacion;
    }

    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }

    public function setCodigoQr(string $codigoQr): void
    {
        $this->codigoQr = $codigoQr;
    }
}