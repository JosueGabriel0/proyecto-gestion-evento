<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonenteModel extends Model
{
    use HasFactory;

    protected $table = 'ponentes';

    protected $fillable = [
        'biografia',
        'user_id',
        'ponencia_id',
    ];

    /**
     * Relación con el usuario (1 ponente pertenece a un usuario)
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    /**
     * Relación con ponencia (1 ponente puede estar en una ponencia)
     */
    public function ponencia()
    {
        return $this->belongsTo(PonenciaModel::class);
    }
}