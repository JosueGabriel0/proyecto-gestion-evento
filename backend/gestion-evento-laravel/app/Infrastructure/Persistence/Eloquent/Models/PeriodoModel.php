<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoModel extends Model
{
    protected $table = 'periodos';
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'periodo_id',
    ];

    public function eventos()
    {
        return $this->hasMany(EventoModel::class, 'evento_id', 'id');
    }
}