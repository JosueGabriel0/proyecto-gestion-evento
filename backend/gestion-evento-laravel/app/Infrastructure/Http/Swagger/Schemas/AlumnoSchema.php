<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Alumno",
 *     type="object",
 *     title="Alumno",
 *     description="Datos de un alumno asociado al usuario",
 *
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="userId", type="integer", example=11),
 *
 *     @OA\Property(
 *         property="codigoUniversitario",
 *         type="string",
 *         nullable=true,
 *         example="20201234"
 *     ),
 *
 *     @OA\Property(
 *         property="carrera",
 *         type="string",
 *         example="Ingeniería de Sistemas"
 *     ),
 *
 * )
 */
class AlumnoSchema {}