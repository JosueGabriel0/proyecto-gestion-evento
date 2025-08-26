<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\AlumnoModel;
use App\Infrastructure\Persistence\Eloquent\Models\JuradoModel;
use App\Infrastructure\Persistence\Eloquent\Models\PersonaModel;
use App\Infrastructure\Persistence\Eloquent\Models\RoleModel;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Persona;
use App\Models\Alumno;
use App\Models\Jurado;

class RolesUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // ====== ROLES ======
        $roles = [
            'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN',
            'ROLE_ALUMNO',
            'ROLE_JURADO'
        ];

        foreach ($roles as $rol) {
            RoleModel::firstOrCreate(['nombre' => $rol]);
        }

        // ====== ADMIN ======
        $admin = UserModel::firstOrCreate(
            ['email' => 'admin@jornada.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_ADMIN')->first()->id
            ]
        );

        PersonaModel::firstOrCreate([
            'user_id' => $admin->id,
            'nombres' => 'Admin',
            'apellidos' => 'Sistema',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '00000001',
            'telefono' => '999111222',
            'direccion' => 'Oficina Central',
            'correoElectronico' => $admin->email,
            'fotoPerfil' => 'admin.png',
            'fechaNacimiento' => '1990-01-01'
        ]);

        // ====== SUPER ADMIN ======
        $superAdmin = UserModel::firstOrCreate(
            ['email' => 'superadmin@jornada.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('super123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_SUPER_ADMIN')->first()->id
            ]
        );

        PersonaModel::firstOrCreate([
            'user_id' => $superAdmin->id,
            'nombres' => 'Super',
            'apellidos' => 'Admin',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '00000002',
            'telefono' => '999333444',
            'direccion' => 'Oficina Principal',
            'correoElectronico' => $superAdmin->email,
            'fotoPerfil' => 'superadmin.png',
            'fechaNacimiento' => '1985-05-05'
        ]);

        // ====== ALUMNO ======
        $alumno = UserModel::firstOrCreate(
            ['email' => 'alumno@jornada.com'],
            [
                'name' => 'Alumno Demo',
                'password' => Hash::make('alumno123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_ALUMNO')->first()->id
            ]
        );

        PersonaModel::firstOrCreate([
            'user_id' => $alumno->id,
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '12345678',
            'telefono' => '987654321',
            'direccion' => 'Av. Universitaria 123',
            'correoElectronico' => $alumno->email,
            'fotoPerfil' => 'alumno.png',
            'fechaNacimiento' => '2000-03-15'
        ]);

        AlumnoModel::firstOrCreate([
            'user_id' => $alumno->id,
            'codigo_qr' => 'QR_ALUMNO_123',
            'carrera' => 'Ingeniería de Sistemas',
            'ciclo' => 'VIII'
        ]);

        // ====== JURADO ======
        $jurado = UserModel::firstOrCreate(
            ['email' => 'jurado@jornada.com'],
            [
                'name' => 'Jurado Demo',
                'password' => Hash::make('jurado123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_JURADO')->first()->id
            ]
        );

        PersonaModel::firstOrCreate([
            'user_id' => $jurado->id,
            'nombres' => 'María',
            'apellidos' => 'Ramírez',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '87654321',
            'telefono' => '912345678',
            'direccion' => 'Av. Ciencia 456',
            'correoElectronico' => $jurado->email,
            'fotoPerfil' => 'jurado.png',
            'fechaNacimiento' => '1980-10-20'
        ]);

        JuradoModel::firstOrCreate([
            'user_id' => $jurado->id,
            'especialidad' => 'Ciencias Computacionales'
        ]);
    }
}