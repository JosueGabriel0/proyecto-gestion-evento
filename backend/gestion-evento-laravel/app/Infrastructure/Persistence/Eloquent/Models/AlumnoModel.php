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
        'codigo_qr',
        'carrera',
        'ciclo',
    ];

    // 🔗 Relaciones

    // Un alumno pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    // Un alumno puede tener varias ponencias
    public function ponencias()
    {
        return $this->hasMany(PonenciaModel::class);
    }
}