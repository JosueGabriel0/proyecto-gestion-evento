<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Facultad",
 *     type="object",
 *     title="Facultad",
 *     description="Facultad del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Example"),
 *     @OA\Property(property="codigo", type="string", example="Example"),
 *     @OA\Property(property="foto", type="string", format="url", example="http://localhost/storage/roles/admin.png"),
 *     @OA\Property(property="filialId", type="integer", example="1"),
 * )
 */
class FacultadSchema {}