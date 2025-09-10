<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'id');
    }

    public function escuela()
    {
        return $this->belongsTo(EscuelaModel::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(NotificacionModel::class, 'user_id', 'id');
    }

    public function persona()
    {
        return $this->hasOne(PersonaModel::class, 'user_id', 'id');
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaModel::class, 'user_id', 'id');
    }

    public function ponente()
    {
        return $this->hasOne(PonenteModel::class, 'user_id', 'id');
    }

    public function alumno()
    {
        return $this->hasOne(AlumnoModel::class, 'user_id', 'id');
    }

    public function jurado()
    {
        return $this->hasOne(JuradoModel::class, 'user_id', 'id');
    }
    /**
     * Convierte desde una Entidad de Dominio a un UserModel (útil para persistir)
     */
    public static function fromDomainEntity(User $user): self
    {
        $model = new self();

        if ($user->getId() !== null) {
            $model->id = $user->getId(); // 👈 mapeamos si ya existe
        }

        $model->email = (string) $user->getEmail();
        $model->password = (string) $user->getPassword();
        $model->role_id = $user->getRoleId();

        return $model;
    }
}
