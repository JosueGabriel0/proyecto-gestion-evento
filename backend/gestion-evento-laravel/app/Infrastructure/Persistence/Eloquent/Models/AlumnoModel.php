<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\Alumno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoModel extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'user_id',
        'codigo_universitario',
    ];

    /**
     * 🔗 Relación: Un alumno pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    /**
     * 🔁 Convierte el modelo Eloquent a la entidad de dominio Alumno
     */
    public function toDomain(): Alumno
    {
        return new Alumno(
            id: $this->id,
            userId: $this->user_id,
            codigo_universitario: $this->codigo_universitario
        );
    }
}