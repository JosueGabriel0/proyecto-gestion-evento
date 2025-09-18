<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class EventoModel extends Model
{
    protected $table = 'eventos';
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_evento_id',
        'tipos_evento',
        'fecha',
        'lugar',
        'latitud',
        'longitud',
        'foto',
        'codigo_qr'
    ];

    public function asistencias()
    {
        return $this->hasMany(AsistenciaModel::class, 'asistencia_id', 'id');
    }

    public function escuela()
    {
        return $this->belongsTo(EscuelaModel::class);
    }
}