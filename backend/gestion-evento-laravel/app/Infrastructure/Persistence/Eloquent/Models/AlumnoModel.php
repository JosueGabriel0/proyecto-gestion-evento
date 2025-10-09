<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoModel extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'user_id',
        'codigo_universitario',
        'carrera',
        'ciclo',
    ];

    // 🔗 Relaciones

    // Un alumno pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}