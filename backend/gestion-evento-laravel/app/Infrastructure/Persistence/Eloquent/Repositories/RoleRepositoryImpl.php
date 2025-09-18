<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\RoleRepository;
use App\Domain\Entities\Role;
use App\Infrastructure\Persistence\Eloquent\Models\RoleModel;

class RoleRepositoryImpl implements RoleRepository
{
    public function all(): array
    {
        return RoleModel::all()
            ->map(fn(RoleModel $model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function find(int $id): ?Role
    {
        $model = RoleModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(Role $role): Role
    {
        $model = new RoleModel();
        $model->nombre = $role->getNombre();
        $model->foto = $role->getFoto();
        $model->save();

        return $this->mapToEntity($model);
    }

    public function update(Role $role): Role
    {
        $model = RoleModel::findOrFail($role->getId());
        $model->nombre = $role->getNombre();
        $model->foto = $role->getFoto();
        $model->save();

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        RoleModel::destroy($id);
    }

    /**
     * Convierte un Eloquent Model a Domain Entity
     */
    private function mapToEntity(RoleModel $model): Role
    {
        return new Role(
            $model->id,
            $model->nombre,
            $model->foto // ✅ pasamos foto desde el modelo a la entidad
        );
    }
}
