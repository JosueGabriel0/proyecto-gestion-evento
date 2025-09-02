<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilialModel extends Model
{
    use HasFactory;

    protected $table = 'filiales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'foto',
    ];

    // Relación: Una Filial tiene muchas Facultades
    public function facultades()
    {
        return $this->hasMany(FacultadModel::class);
    }
}