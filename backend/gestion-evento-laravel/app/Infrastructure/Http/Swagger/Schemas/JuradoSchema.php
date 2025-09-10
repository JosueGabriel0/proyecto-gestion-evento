<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Jurado",
 *     type="object",
 *     title="Jurado",
 *     description="Datos de un jurado asociado a un usuario y sus evaluaciones",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         nullable=true,
 *         example=7
 *     ),
 *
 *     @OA\Property(
 *         property="userId",
 *         type="integer",
 *         nullable=true,
 *         example=22,
 *         description="Identificador del usuario que es jurado"
 *     ),
 *
 *     @OA\Property(
 *         property="especialidad",
 *         type="string",
 *         example="Inteligencia Artificial",
 *         description="Especialidad principal del jurado"
 *     )
 * )
 */
class JuradoSchema {}