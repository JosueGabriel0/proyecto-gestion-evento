<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\Jurado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuradoModel extends Model
{
    use HasFactory;

    protected $table = 'jurados';

    protected $fillable = [
        'user_id',
        'especialidad',
    ];

    /**
     * 🔗 Relación: un jurado pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    /**
     * 🔗 Relación: un jurado puede realizar varias evaluaciones
     */
    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionModel::class, 'jurado_id', 'id');
    }

    /**
     * 🔁 Convierte el modelo Eloquent a la entidad de dominio Jurado
     */
    public function toDomain(): Jurado
    {
        return new Jurado(
            id: $this->id,
            userId: $this->user_id,
            especialidad: $this->especialidad
        );
    }
}