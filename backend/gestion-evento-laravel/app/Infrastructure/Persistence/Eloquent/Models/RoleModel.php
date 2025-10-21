<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\Role;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'foto',
    ];

    /**
     * 🔹 Relación: un rol tiene muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(UserModel::class, 'role_id', 'id');
    }

    /**
     * 🔹 Convierte el modelo Eloquent a una entidad de dominio Role
     */
    public function toDomain(): Role
    {
        return new Role(
            id: $this->id,
            nombre: $this->nombre,
            foto: $this->foto
        );
    }
}