<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

use OpenApi\Annotations as OA;
/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     required={"id", "nombre"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID único del rol",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del rol",
 *         example="Administrador"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación",
 *         example="2024-01-15T10:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización",
 *         example="2024-01-15T10:30:00Z"
 *     )
 * )
 */

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'foto'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'role_id', 'id');
    }

    public function personas()
    {
        return $this->hasMany(PersonaModel::class, 'role_id', 'id');
    }
}