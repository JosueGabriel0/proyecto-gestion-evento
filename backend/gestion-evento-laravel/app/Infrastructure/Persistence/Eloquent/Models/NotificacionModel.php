<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionModel extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'mensaje',
        'leido',
    ];

    // 🔗 Relaciones

    // Una notificación pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}