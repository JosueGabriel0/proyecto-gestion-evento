<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

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

    // 🔗 Relaciones

    // Un jurado pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    // Un jurado puede realizar varias evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionModel::class);
    }
}