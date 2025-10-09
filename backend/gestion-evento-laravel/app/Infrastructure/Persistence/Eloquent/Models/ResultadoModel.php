<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoModel extends Model
{
    use HasFactory;

    protected $table = 'resultados';

    protected $fillable = [
        'ponencia_id',
        'puntaje_final',
        'posicion',
        'generado_en',
    ];

    public $timestamps = false; // porque solo tiene generado_en

    // 🔗 Relaciones

    // Un resultado pertenece a una ponencia
    public function ponencia()
    {
        return $this->belongsTo(PonenciaModel::class);
    }
}