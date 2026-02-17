<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Ponencia;
use App\Domain\Repositories\PonenciaRepository;
use App\Infrastructure\Persistence\Eloquent\Models\PonenciaModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PonenciaReposiutoryImpl implements PonenciaRepository
{
    public function all(): array
    {
        $ponencias = PonenciaModel::all()->map(fn(PonenciaModel $model) => $this->mapToEntity($model))->toArray();
        return $ponencias;
    }

    public function find(int $id): ?Ponencia
    {
        $ponenciaEncontrada = PonenciaModel::find($id);
        if ($ponenciaEncontrada) {
            return $this->mapToEntity($ponenciaEncontrada);
        } else {
            return null;
        }
    }

    public function create(Ponencia $ponencia): Ponencia
    {
        $nuevaPonencia = new PonenciaModel();
        $nuevaPonencia->evento_id = $ponencia->getEventoId();
        $nuevaPonencia->categoria_id = $ponencia->getCategoriaId();
        $nuevaPonencia->ponente = $ponencia->getPonente();
        $nuevaPonencia->institucion = $ponencia->getInstitucion();
        $nuevaPonencia->archivo_presentacion = $ponencia->getArchivoPresentacion();
        $nuevaPonencia->foto = $ponencia->getFoto();
        $nuevaPonencia->codigo_qr = $ponencia->getCodigoQr();
        $nuevaPonencia->save();
        return $this->mapToEntity($nuevaPonencia);
    }

    public function update(int $id, Ponencia $ponencia): Ponencia
    {
        $ponenciaEncontrada = PonenciaModel::findOrFail($id);
        $ponenciaEncontrada->nombre = $ponencia->getNombre();
        $ponenciaEncontrada->evento_id = $ponencia->getEventoId();
        $ponenciaEncontrada->categoria_id = $ponencia->getCategoriaId();
        $ponenciaEncontrada->ponente = $ponencia->getPonente();
        $ponenciaEncontrada->institucion = $ponencia->getInstitucion();
        $ponenciaEncontrada->archivo_presentacion = $ponencia->getArchivoPresentacion();
        $ponenciaEncontrada->foto = $ponencia->getFoto();
        $ponenciaEncontrada->codigo_qr = $ponencia->getCodigoQr();
        $ponenciaEncontrada->save();

        return $this->mapToEntity($ponenciaEncontrada);
    }

    public function delete(int $id): void
    {
        PonenciaModel::destroy($id);
    }

    public function getPonenciasPaginated(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return PonenciaModel::paginate(
            $perPage,
            ['*'],
            'page',
            $page
        )->through(fn(PonenciaModel $model) => $this->mapToEntity($model));
    }

    public function searchPonencias(string $term, int $perPage = 10): LengthAwarePaginator
    {
        return PonenciaModel::where('nombre', 'LIKE', "%{$term}%")
            ->paginate($perPage)
            ->through(fn(PonenciaModel $model) => $this->mapToEntity($model));
    }

    private function mapToEntity(PonenciaModel $model): Ponencia
    {
        return new Ponencia(
            $model->id,
            $model->nombre,
            $model->eventoId,
            $model->categoriaId,
            $model->ponente,
            $model->institucion,
            $model->archivoPresentacion,
            $model->foto,
            $model->codigoQr,
        );
    }
}
