<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaModel extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'modo_contrato',
        'modalidad_estudio',
        'ciclo',
        'grupo',
        'fecha_matricula',
        'estado',
    ];

    /**
     * 🔹 Relación: una matrícula puede tener muchos alumnos.
     */
    public function alumnos()
    {
        return $this->hasMany(AlumnoModel::class, 'matricula_id');
    }
}