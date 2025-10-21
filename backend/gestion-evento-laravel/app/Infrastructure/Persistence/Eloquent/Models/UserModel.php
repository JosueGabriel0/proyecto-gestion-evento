<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\User;
use App\Domain\Entities\Persona;
use App\Domain\Entities\Role;
use App\Domain\Entities\Alumno;
use App\Domain\Entities\Jurado;
use App\Domain\Entities\Ponente;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use DateTime;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'role_id',
        'escuela_id',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ======================
    // 🔹 Relaciones Eloquent
    // ======================

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'id');
    }

    public function escuela()
    {
        return $this->belongsTo(EscuelaModel::class, 'escuela_id', 'id');
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

    // ======================
    // 🔹 JWT Claims personalizados
    // ======================

    public function getJWTCustomClaims(): array
    {
        return [
            'idUsuario' => $this->id,
            'role'      => $this->role ? $this->role->nombre : null,
            'email'     => $this->email,
        ];
    }

    // ======================
    // 🔹 Conversión a Entidad de Dominio
    // ======================

    public function toDomain(): User
    {
        $user = new User(
            id: $this->id,
            email: $this->email,
            password: $this->password,
            escuelaId: $this->escuela_id,
            roleId: $this->role_id,
            emailVerifiedAt: $this->email_verified_at ? new DateTime($this->email_verified_at) : null,
            rememberToken: $this->remember_token,
            persona: $this->persona ? $this->persona->toDomain() : null,
            role: $this->role ? $this->role->toDomain() : null,
            alumno: $this->alumno ? $this->alumno->toDomain() : null,
            jurado: $this->jurado ? $this->jurado->toDomain() : null,
            ponente: $this->ponente ? $this->ponente->toDomain() : null
        );

        $user->setCreatedAt($this->created_at ? new DateTime($this->created_at) : null);
        $user->setUpdatedAt($this->updated_at ? new DateTime($this->updated_at) : null);

        return $user;
    }
}
