<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'foto'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'role_id', 'id');
    }
}