<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Escuela;
use App\Domain\Repositories\EscuelaRepository;
use App\Infrastructure\Persistence\Eloquent\Models\EscuelaModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

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

    public function getEscuelasPaginated(int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = EscuelaModel::query()
            ->with('facultad')
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Convertir los modelos a entidades
        $entities = $paginator->getCollection()->map(fn($model) => $this->toEntity($model));

        // Crear un nuevo paginador con las entidades
        return new Paginator(
            $entities,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            [
                'path' => $paginator->path(),
                'pageName' => 'page',
            ]
        );
    }

    public function searchEscuela(string $term, int $perPage): LengthAwarePaginator
    {
        $paginator = EscuelaModel::query()
            ->where('nombre', 'like', "%{$term}%")
            ->orWhere('codigo', 'like', "%{$term}%")
            ->with('facultad')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        // Convertir los modelos a entidades
        $entities = $paginator->getCollection()->map(fn($model) => $this->toEntity($model));

        // Crear un nuevo paginador con las entidades
        return new Paginator(
            $entities,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            [
                'path' => $paginator->path(),
                'pageName' => 'page',
            ]
        );
    }

    private function toEntity(EscuelaModel $model): Escuela
    {
        return new Escuela(
            id: $model->id,
            nombre: $model->nombre,
            codigo: $model->codigo,
            facultad_id: $model->facultad_id,
            foto: $model->foto
        );
    }
}