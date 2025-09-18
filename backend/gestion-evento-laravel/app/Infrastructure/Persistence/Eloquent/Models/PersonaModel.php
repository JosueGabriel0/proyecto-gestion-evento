<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaModel extends Model
{
    protected $table = 'personas';
    protected $fillable = [
        'nombres',
        'apellidos',
        'tipoDocumento',
        'numeroDocumento',
        'telefono',
        'direccion',
        'correoElectronico',
        'fotoPerfil',
        'fechaNacimiento',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
