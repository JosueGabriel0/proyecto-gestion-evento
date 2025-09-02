<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Facultad;
use App\Domain\Repositories\FacultadRepository;
use App\Infrastructure\Persistence\Eloquent\Models\FacultadModel;

class FacultadRepositoryImpl implements FacultadRepository
{
    public function findById(int $id): ?Facultad
    {
        $model = FacultadModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function getAll(): array
    {
        return FacultadModel::all()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Facultad $facultad): Facultad
    {
        $model = new FacultadModel();

        $model->nombre = $facultad->getNombre();
        $model->codigo = $facultad->getCodigo();
        $model->foto = $facultad->getFoto();
        $model->filial_id = $facultad->getFilialId();
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        FacultadModel::destroy($id);
    }

    public function update(int $id, Facultad $facultad): Facultad
    {
        $facultadEncontrada = FacultadModel::findOrFail($id);
        $facultadEncontrada->nombre = $facultad->getNombre();
        $facultadEncontrada->direccion = $facultad->getCodigo();
        $facultadEncontrada->telefono = $facultad->getFilialId();
        $facultadEncontrada->foto = $facultad->getFoto();
        $facultadEncontrada->save();

        return $this->toEntity($facultadEncontrada);
    }

    /**
     * 🔄 Convierte Model → Entity
     */
    private function toEntity(FacultadModel $model): Facultad
    {
        return new Facultad(
            id: $model->id,
            nombre: $model->nombre,
            codigo: $model->codigo,
            filialId: $model->filial_id,
            foto: $model->foto
        );
    }
}
