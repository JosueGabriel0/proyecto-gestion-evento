<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionModel extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'ponencia_id',
        'jurado_id',
        'criterio',
        'puntaje',
        'observaciones',
    ];

    // 🔗 Relaciones

    // Una evaluación pertenece a una ponencia
    public function ponencia()
    {
        return $this->belongsTo(PonenciaModel::class);
    }

    // Una evaluación pertenece a un jurado
    public function jurado()
    {
        return $this->belongsTo(JuradoModel::class);
    }
}