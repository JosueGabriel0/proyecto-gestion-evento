<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\Ponente;
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
     * 🔗 Relación: un ponente pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    /**
     * 🔗 Relación: un ponente pertenece a una ponencia
     */
    public function ponencia()
    {
        return $this->belongsTo(PonenciaModel::class, 'ponencia_id', 'id');
    }

    /**
     * 🔁 Convierte el modelo Eloquent a una entidad de dominio Ponente
     */
    public function toDomain(): Ponente
    {
        return new Ponente(
            id: $this->id,
            biografia: $this->biografia,
            userId: $this->user_id,
            ponenciaId: $this->ponencia_id
        );
    }
}