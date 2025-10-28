<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuario",
 *     description="Entidad que representa a un usuario del sistema, con sus datos básicos y relaciones asociadas.",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="email", type="string", example="usuario@universidad.edu"),
 *     @OA\Property(property="password", type="string", example="********"),
 *     @OA\Property(property="escuelaId", type="integer", example=3, description="ID de la escuela a la que pertenece el usuario"),
 *     @OA\Property(property="roleId", type="integer", nullable=true, example=2),
 *     @OA\Property(property="emailVerifiedAt", type="string", format="date-time", nullable=true, example="2025-03-15T10:30:00Z"),
 *     @OA\Property(property="rememberToken", type="string", nullable=true, example="xyz123token"),
 *
 *     @OA\Property(
 *         property="persona",
 *         ref="#/components/schemas/Persona",
 *         description="Información personal del usuario"
 *     ),
 *
 *     @OA\Property(
 *         property="role",
 *         ref="#/components/schemas/Role",
 *         description="Rol asignado al usuario"
 *     ),
 *
 *     @OA\Property(
 *         property="alumno",
 *         ref="#/components/schemas/Alumno",
 *         description="Datos del alumno (si aplica)"
 *     ),
 *
 *     @OA\Property(
 *         property="jurado",
 *         ref="#/components/schemas/Jurado",
 *         description="Datos del jurado (si aplica)"
 *     ),
 *
 *     @OA\Property(
 *         property="ponente",
 *         ref="#/components/schemas/Ponente",
 *         description="Datos del ponente (si aplica)"
 *     ),
 *
 *     example={
 *         "id": 1,
 *         "email": "usuario@universidad.edu",
 *         "escuelaId": 3,
 *         "roleId": 2,
 *         "emailVerifiedAt": "2025-03-15T10:30:00Z",
 *         "persona": {
 *             "nombres": "Juan",
 *             "apellidos": "Pérez",
 *             "correoElectronico": "juan.perez@uni.edu"
 *         },
 *         "role": {
 *             "id": 2,
 *             "nombre": "ROLE_ESTUDIANTE"
 *         },
 *         "alumno": {
 *             "codigoUniversitario": "20201234",
 *             "carrera": "Ingeniería de Sistemas",
 *             
 *         },
 *         "jurado": null,
 *         "ponente": null
 *     }
 * )
 */
class UserSchema {}