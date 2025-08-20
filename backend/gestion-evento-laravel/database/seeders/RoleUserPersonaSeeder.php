<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;

class RoleUserPersonaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear roles
        $superAdminRole = Role::firstOrCreate(['nombre' => 'ROLE_SUPER_ADMIN']);
        $adminRole = Role::firstOrCreate(['nombre' => 'ROLE_ADMIN']);
        $userRole = Role::firstOrCreate(['nombre' => 'ROLE_USER']);
        $juryRole = Role::firstOrCreate(['nombre' => 'ROLE_JURY']);

        // 2. Crear usuarios y personas

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'role_id' => $superAdminRole->id
            ]
        );

        Persona::firstOrCreate(
            ['correoElectronico' => 'superadmin@example.com'],
            [
                'nombres' => 'Super',
                'apellidos' => 'Admin',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'telefono' => '999999999',
                'direccion' => 'Av. Principal 123',
                'correoElectronico' => 'superadmin@example.com',
                'fotoPerfil' => 'default.jpg',
                'fechaNacimiento' => '1980-01-01',
                'user_id' => $superAdmin->id, // vincular al usuario
                'role_id' => $superAdminRole->id // vincular al rol
            ]
        );

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id
            ]
        );

        Persona::firstOrCreate(
            ['correoElectronico' => 'admin@example.com'],
            [
                'nombres' => 'Admin',
                'apellidos' => 'User',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '87654321',
                'telefono' => '988888888',
                'direccion' => 'Av. Secundaria 456',
                'correoElectronico' => 'admin@example.com',
                'fotoPerfil' => 'default.jpg',
                'fechaNacimiento' => '1990-05-15',
                'user_id' => $admin->id,
                'role_id' => $adminRole->id
            ]
        );

        // Usuario normal
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('user123'),
                'role_id' => $userRole->id
            ]
        );

        Persona::firstOrCreate(
            ['correoElectronico' => 'user@example.com'],
            [
                'nombres' => 'Normal',
                'apellidos' => 'User',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '11223344',
                'telefono' => '977777777',
                'direccion' => 'Av. Tercera 789',
                'correoElectronico' => 'user@example.com',
                'fotoPerfil' => 'default.jpg',
                'fechaNacimiento' => '2000-08-20',
                'user_id' => $user->id,
                'role_id' => $userRole->id
            ]
        );

        // Jurado
        $jury = User::firstOrCreate(
            ['email' => 'jury@example.com'],
            [
                'name' => 'Jury',
                'password' => Hash::make('jury123'),
                'role_id' => $juryRole->id
            ]
        );

        Persona::firstOrCreate(
            ['correoElectronico' => 'jury@example.com'],
            [
                'nombres' => 'Jury',
                'apellidos' => 'User',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '11223341',
                'telefono' => '977777777',
                'direccion' => 'Av. Tercera 789',
                'correoElectronico' => 'jury@example.com',
                'fotoPerfil' => 'default.jpg',
                'fechaNacimiento' => '2000-08-20',
                'user_id' => $jury->id,
                'role_id' => $juryRole->id
            ]
        );
    }
}