<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Escuela",
 *     type="object",
 *     title="Escuela",
 *     description="Escuela del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Example"),
 *     @OA\Property(property="codigo", type="string", example="Example"),
 *     @OA\Property(property="facultad_id", type="integer", example="1"),
 *     @OA\Property(property="foto", type="string", format="url", example="http://localhost/storage/roles/admin.png"),
 * )
 */
class EscuelaSchema {}