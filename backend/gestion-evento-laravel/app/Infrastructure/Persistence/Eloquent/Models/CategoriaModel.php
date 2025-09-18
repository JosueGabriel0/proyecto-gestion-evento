<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'estado',
        'parent_id',
    ];

    public function ponencias()
    {
        return $this->hasMany(PonenciaModel::class);
    }

    public function alumno()
    {
        return $this->belongsTo(AlumnoModel::class);
    }
}
