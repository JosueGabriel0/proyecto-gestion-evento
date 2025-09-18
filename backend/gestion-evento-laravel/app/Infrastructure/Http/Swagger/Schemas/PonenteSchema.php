<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Ponente",
 *     type="object",
 *     title="Ponente",
 *     description="Datos del ponente asociado a un usuario y una ponencia",
 *
 *     @OA\Property(property="id", type="integer", nullable=true, example=3),
 *
 *     @OA\Property(
 *         property="biografia",
 *         type="string",
 *         nullable=true,
 *         example="Ingeniero con 10 años de experiencia en inteligencia artificial."
 *     ),
 *
 *     @OA\Property(property="userId", type="integer", nullable=true, example=12),
 *
 *     @OA\Property(
 *         property="ponenciaId",
 *         type="integer",
 *         example=45,
 *         description="Identificador de la ponencia asignada"
 *     )
 * )
 */
class PonenteSchema {}