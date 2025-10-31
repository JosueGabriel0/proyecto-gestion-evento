<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;
use App\Infrastructure\Persistence\Eloquent\Models\EscuelaModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EscuelaRepositoryImpl implements EscuelaRepository
{
    public function findById(int $id): ?Escuela
    {
        $escuelaEncontrada = EscuelaModel::find($id);
        return $escuelaEncontrada ? $this->toEntity($escuelaEncontrada) : null;
    }

    public function findAll(): array
    {
        $escuelasEncontradas = EscuelaModel::all()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
        return $escuelasEncontradas;
    }

    public function save(Escuela $escuela): Escuela
    {
        $nuevaEscuela = new EscuelaModel();
        $nuevaEscuela->nombre = $escuela->getNombre();
        $nuevaEscuela->codigo = $escuela->getCodigo();
        $nuevaEscuela->facultad_id = $escuela->getFacultadId();
        $nuevaEscuela->foto = $escuela->getFoto();

        $nuevaEscuela->save();

        return $this->toEntity($nuevaEscuela);
    }

    public function getAllEscuelasByFacultadId(int $id): array
    {
        return EscuelaModel::where('facultad_id', $id)
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function update(int $id, Escuela $escuela): Escuela
    {
        $escuelaActualizada = EscuelaModel::findOrFail($id);
        $escuelaActualizada->nombre = $escuela->getNombre();
        $escuelaActualizada->codigo = $escuela->getCodigo();
        $escuelaActualizada->facultad_id = $escuela->getFacultadId();
        $escuelaActualizada->foto = $escuela->getFoto();
        $escuelaActualizada->save();

        return $this->toEntity($escuelaActualizada);
    }

    public function delete(int $id): void
    {
        EscuelaModel::destroy($id);
    }

    public function getEscuelasPaginated(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return EscuelaModel::paginate(
            $perPage,
            ['*'],
            'page',
            $page
        )->through(fn(EscuelaModel $model) => $this->toEntity($model));
    }

    public function searchEscuela(string $term, int $perPage = 10): LengthAwarePaginator
    {
        return EscuelaModel::where('nombre', 'LIKE', "%{$term}%")
            ->paginate($perPage)
            ->through(fn(EscuelaModel $model) => $this->toEntity($model));
    }

    private function toEntity(EscuelaModel $model): Escuela
    {
        return new Escuela(
            id: $model->id,
            nombre: $model->nombre,
            codigo: $model->codigo,
            facultadId: $model->facultad_id,
            foto: $model->foto
        );
    }
}
