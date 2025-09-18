<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscuelaModel extends Model
{
    use HasFactory;

    protected $table = 'escuelas';

    protected $fillable = [
        'nombre',
        'codigo',
        'facultad_id',
        'foto',
    ];

    // Relación: Una Escuela pertenece a una Facultad
    public function facultad()
    {
        return $this->belongsTo(FacultadModel::class);
    }

     public function users()
    {
        return $this->hasMany(UserModel::class);
    }

    public function eventos()
    {
        return $this->hasMany(EventoModel::class);
    }
}