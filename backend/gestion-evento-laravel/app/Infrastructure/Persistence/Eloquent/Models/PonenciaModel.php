<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonenciaModel extends Model
{
    use HasFactory;

    protected $table = 'ponencias';

    protected $fillable = [
        'nombre',
        'evento_id',
        'categoria_id',
        'ponente',
        'institucion',
        'archivo_presentacion',
        'foto',
        'codigo_qr',
    ];

    /**
     * Una ponencia pertenece a un evento (1:1)
     */
    public function evento()
    {
        return $this->belongsTo(EventoModel::class, 'evento_id');
    }

    /**
     * Una ponencia pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaModel::class, 'categoria_id');
    }

    /**
     * Relación opcional: una ponencia puede tener varios asistentes o evaluaciones (si aplica)
     */
    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionModel::class, 'ponencia_id');
    }

    /**
     * Accessor para mostrar nombre del ponente en mayúsculas
     */
    public function getPonenteUppercaseAttribute()
    {
        return strtoupper($this->ponente);
    }
}