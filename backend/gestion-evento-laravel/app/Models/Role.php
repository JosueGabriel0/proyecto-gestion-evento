<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nombre'];

    // Rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Rol tiene una persona
    public function persona()
    {
        return $this->hasOne(Persona::class);
    }
}