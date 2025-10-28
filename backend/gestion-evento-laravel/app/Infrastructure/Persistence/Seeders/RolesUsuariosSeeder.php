<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\AlumnoModel;
use App\Infrastructure\Persistence\Eloquent\Models\JuradoModel;
use App\Infrastructure\Persistence\Eloquent\Models\MatriculaModel;
use App\Infrastructure\Persistence\Eloquent\Models\PersonaModel;
use App\Infrastructure\Persistence\Eloquent\Models\RoleModel;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // Filiales
        // ============================
        DB::table('filiales')->insert([
            [
                'nombre' => 'Campus Lima',
                'direccion' => 'Av. Universitaria 123, Lima',
                'telefono' => '01-1234567',
                'email' => 'lima@upu.edu.pe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Campus Tarapoto',
                'direccion' => 'Jr. Principal 456, Tarapoto',
                'telefono' => '042-7654321',
                'email' => 'tarapoto@upu.edu.pe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Campus Juliaca',
                'direccion' => 'Av. Puno 789, Juliaca',
                'telefono' => '051-9876543',
                'email' => 'juliaca@upu.edu.pe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================
        // Facultades
        // ============================
        DB::table('facultades')->insert([
            // Campus Lima
            [
                'nombre' => 'Facultad de Ingeniería y Arquitectura',
                'codigo' => 'FIA',
                'filial_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Facultad de Ciencias Empresariales',
                'codigo' => 'FCE',
                'filial_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Campus Tarapoto
            [
                'nombre' => 'Facultad de Ingeniería y Arquitectura',
                'codigo' => 'FIA-T',
                'filial_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Facultad de Ciencias Empresariales',
                'codigo' => 'FCE-T',
                'filial_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Campus Juliaca
            [
                'nombre' => 'Facultad de Ingeniería y Arquitectura',
                'codigo' => 'FIA-J',
                'filial_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Facultad de Ciencias Empresariales',
                'codigo' => 'FCE-J',
                'filial_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================
        // Escuelas
        // ============================
        DB::table('escuelas')->insert([
            // FIA - Campus Lima
            [
                'nombre' => 'Escuela Profesional de Ingeniería de Sistemas',
                'codigo' => 'SIS',
                'facultad_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escuela Profesional de Ingeniería Civil',
                'codigo' => 'CIV',
                'facultad_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escuela Profesional de Ingeniería de Industrias Alimentarias',
                'codigo' => 'ALI',
                'facultad_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // FCE - Campus Lima
            [
                'nombre' => 'Escuela Profesional de Administración de Empresas',
                'codigo' => 'ADM',
                'facultad_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escuela Profesional de Contabilidad',
                'codigo' => 'CON',
                'facultad_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escuela Profesional de Marketing',
                'codigo' => 'MKT',
                'facultad_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================
        // Roles
        // ============================
        $roles = ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_ALUMNO', 'ROLE_JURADO', 'ROLE_PONENTE'];
        foreach ($roles as $rol) {
            RoleModel::firstOrCreate(['nombre' => $rol]);
        }

        // ============================
        // ADMIN
        // ============================
        $admin = UserModel::firstOrCreate(
            ['email' => 'admin@jornada.com'],
            [
                'password' => Hash::make('admin123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_ADMIN')->first()->id,
                'escuela_id' => 1, // Ingeniería de Sistemas
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
            'pais' => 'Peru',
            'religion' => 'Adventista',
            'correoElectronico' => $admin->email,
            'correoInstitucional' => 'admin.user@upeu.edu.pe',
            'fotoPerfil' => 'admin.png',
            'fechaNacimiento' => '1990-01-01'
        ]);

        // ============================
        // SUPER ADMIN
        // ============================
        $superAdmin = UserModel::firstOrCreate(
            ['email' => 'superadmin@jornada.com'],
            [
                'password' => Hash::make('super123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_SUPER_ADMIN')->first()->id,
                'escuela_id' => 1,
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
            'pais' => 'Peru',
            'religion' => 'Adventista',
            'correoElectronico' => $superAdmin->email,
            'correoInstitucional' => 'admin.user@upeu.edu.pe',
            'fotoPerfil' => 'superadmin.png',
            'fechaNacimiento' => '1985-05-05'
        ]);

        $matricula = MatriculaModel::firstOrCreate([
            'modo_contrato' => 'Ordinario',
            'modalida_estudio' => 'Presencial',
            'ciclo' => 'VIII',
            'grupo' => 'A',
        ]);

        // ============================
        // ALUMNO
        // ============================
        $alumno = UserModel::firstOrCreate(
            ['email' => 'alumno@jornada.com'],
            [
                'password' => Hash::make('alumno123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_ALUMNO')->first()->id,
                'escuela_id' => 1,
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
            'pais' => 'Peru',
            'religion' => 'Adventista',
            'correoElectronico' => $alumno->email,
            'correoInstitucional' => 'admin.user@upeu.edu.pe',
            'fotoPerfil' => 'alumno.png',
            'fechaNacimiento' => '2000-03-15'
        ]);

        AlumnoModel::firstOrCreate([
            'user_id' => $alumno->id,
            'codigo_universitario' => '1234567',
            'matricula_id' => $matricula->id,
        ]);

        // ============================
        // JURADO
        // ============================
        $jurado = UserModel::firstOrCreate(
            ['email' => 'jurado@jornada.com'],
            [
                'password' => Hash::make('jurado123'),
                'role_id' => RoleModel::where('nombre', 'ROLE_JURADO')->first()->id,
                'escuela_id' => 1,
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
            'pais' => 'Peru',
            'religion' => 'Adventista',
            'correoElectronico' => $jurado->email,
            'correoInstitucional' => 'admin.user@upeu.edu.pe',
            'fotoPerfil' => 'jurado.png',
            'fechaNacimiento' => '1980-10-20'
        ]);

        JuradoModel::firstOrCreate([
            'user_id' => $jurado->id,
            'especialidad' => 'Ciencias Computacionales'
        ]);
    }
}
