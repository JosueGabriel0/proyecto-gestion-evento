<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Filial;
use App\Domain\Repositories\FilialRepository;
use App\Infrastructure\Persistence\Eloquent\Models\FilialModel;

class FilialRepositoryImpl implements FilialRepository
{
    public function all(): array
    {
        $filiales = FilialModel::all()->map(fn(FilialModel $model) => $this->mapToEntity($model))->toArray();
        return $filiales;
    }

    public function find(int $id): ?Filial
    {
        $filialEncontrada = FilialModel::find($id);
        if ($filialEncontrada) {
            return $this->mapToEntity($filialEncontrada);
        } else {
            return null;
        }
    }

    public function create(Filial $filial): Filial
    {
        $nuevaFilial = new FilialModel();
        $nuevaFilial->nombre = $filial->getNombre();
        $nuevaFilial->direccion = $filial->getDireccion();
        $nuevaFilial->telefono = $filial->getTelefono();
        $nuevaFilial->email = $filial->getEmail();
        $nuevaFilial->foto = $filial->getFoto();
        $nuevaFilial->save();
        return $this->mapToEntity($nuevaFilial);
    }

    public function update(int $id, Filial $filial): Filial
    {
        $filialEncontrada = FilialModel::findOrFail($id);
        $filialEncontrada->nombre = $filial->getNombre();
        $filialEncontrada->direccion = $filial->getDireccion();
        $filialEncontrada->telefono = $filial->getTelefono();
        $filialEncontrada->email = $filial->getEmail();
        $filialEncontrada->foto = $filial->getFoto();
        $filialEncontrada->save();

        return $this->mapToEntity($filialEncontrada);
    }

    public function delete(int $id): void
    {
        FilialModel::destroy($id);
    }

    private function mapToEntity(FilialModel $model): Filial
    {
        return new Filial(
            $model->id,
            $model->nombre,
            $model->direccion,
            $model->telefono,
            $model->email,
            $model->foto // 👈 aquí pasamos la foto desde el modelo
        );
    }
}
