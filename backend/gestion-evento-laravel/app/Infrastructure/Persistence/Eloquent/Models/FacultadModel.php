<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultadModel extends Model
{
    use HasFactory;

    protected $table = 'facultades';

    protected $fillable = [
        'nombre',
        'codigo',
        'filial_id',
        'foto',
    ];

    // Relación: Una Facultad pertenece a una Filial
    public function filial()
    {
        return $this->belongsTo(FilialModel::class);
    }

    // Relación: Una Facultad tiene muchas Escuelas
    public function escuelas()
    {
        return $this->hasMany(EscuelaModel::class);
    }
}