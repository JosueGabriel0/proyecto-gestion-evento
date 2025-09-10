<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaModel extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'user_id',
        'evento_id',
        'presente',
        'hora_registro',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function periodos()
    {
        return $this->belongsTo(PeriodoModel::class);
    }
}