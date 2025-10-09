<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     title="Usuario",
 *     description="Entidad Usuario del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="email", type="string", format="email", example="admin@ejemplo.com"),
 *     @OA\Property(property="passwordHash", type="string", example="$2y$10$AbCdEf123456..."),
 *     @OA\Property(property="escuelaId", type="integer", example=5),
 *     @OA\Property(property="roleId", type="integer", nullable=true, example=2),
 *     @OA\Property(property="emailVerifiedAt", type="string", format="date-time", nullable=true, example="2025-09-04T10:15:30Z"),
 *     @OA\Property(property="rememberToken", type="string", nullable=true, example="a1b2c3d4e5f6g7"),
 *
 *     @OA\Property(
 *         property="persona",
 *         ref="#/components/schemas/Persona",
 *         description="Datos de la persona asociada"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         ref="#/components/schemas/Role",
 *         description="Rol asignado al usuario"
 *     ),
 *     @OA\Property(
 *         property="ponente",
 *         ref="#/components/schemas/Ponente",
 *         description="Perfil de ponente (si aplica)"
 *     ),
 *     @OA\Property(
 *         property="alumno",
 *         ref="#/components/schemas/Alumno",
 *         description="Perfil de alumno (si aplica)"
 *     ),
 *     @OA\Property(
 *         property="jurado",
 *         ref="#/components/schemas/Jurado",
 *         description="Perfil de jurado (si aplica)"
 *     ),
 *
 *     @OA\Property(
 *         property="foto",
 *         type="string",
 *         format="url",
 *         example="http://localhost/storage/usuarios/admin.png"
 *     )
 * )
 */
class UserWithPersonaSchema {}