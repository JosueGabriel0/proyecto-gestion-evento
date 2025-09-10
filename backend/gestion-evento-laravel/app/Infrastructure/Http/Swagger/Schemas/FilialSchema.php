<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Filial",
 *     type="object",
 *     title="Filial",
 *     description="Filial del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Example"),
 *     @OA\Property(property="direccion", type="string", example="Example"),
 *     @OA\Property(property="telefono", type="string", example="Example"),
 *     @OA\Property(property="email", type="string", example="Example"),
 *     @OA\Property(property="foto", type="string", format="url", example="http://localhost/storage/roles/admin.png")
 * )
 */
class FilialSchema {}