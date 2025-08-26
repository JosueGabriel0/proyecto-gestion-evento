<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'id');
    }

    public function persona()
    {
        return $this->hasOne(PersonaModel::class, 'user_id', 'id');
    }

    /**
     * Convierte el Eloquent Model en una Entidad de Dominio
     */
    public function toDomainEntity(): User
    {
        return new User(
            id: $this->id,   // 👈 importante
            name: $this->name,
            email: new Email($this->email),
            password: new Password($this->password),
            roleId: $this->role_id
        );
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

        $model->name = $user->getName();
        $model->email = (string) $user->getEmail();
        $model->password = (string) $user->getPassword();
        $model->role_id = $user->getRoleId();

        return $model;
    }
}
