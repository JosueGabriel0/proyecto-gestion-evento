<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class PersonaModel extends Model
{
    use HasFactory;

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

    /**
     * 🔁 Convierte el modelo a una entidad de dominio Persona
     */
    public function toDomain(): Persona
    {
        return new Persona(
            id: $this->id,
            nombres: $this->nombres ?? '',
            apellidos: $this->apellidos ?? '',
            tipoDocumento: $this->tipoDocumento ?? 'DNI', // Valor por defecto si viene null
            numeroDocumento: $this->numeroDocumento ?? '',
            telefono: $this->telefono,
            direccion: $this->direccion,
            correoElectronico: $this->correoElectronico ?? '',
            fotoPerfil: $this->fotoPerfil,
            fechaNacimiento: new DateTime($this->fechaNacimiento ?? '2000-01-01')
        );
    }
}