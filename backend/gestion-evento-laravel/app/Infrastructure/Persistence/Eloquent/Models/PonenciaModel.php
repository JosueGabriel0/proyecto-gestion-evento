<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonenciaModel extends Model
{
    use HasFactory;

    protected $table = 'ponencias';

    protected $fillable = [
        'alumno_id',
        'titulo',
        'descripcion',
        'area',
        'horario',
    ];

    // 🔗 Relaciones

    // Una ponencia pertenece a un alumno
    public function categoria()
    {
        return $this->belongsTo(CategoriaModel::class);
    }

    // Una ponencia puede tener muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionModel::class);
    }

    // Una ponencia puede tener un resultado final
    public function resultado()
    {
        return $this->hasOne(ResultadoModel::class);
    }
}