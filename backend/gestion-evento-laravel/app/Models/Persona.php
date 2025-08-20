<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
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
        'role_id',
    ];

    // Persona pertenece a un rol
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Persona tiene un usuario
    public function user()
    {
        return $this->hasOne(User::class);
    }
}