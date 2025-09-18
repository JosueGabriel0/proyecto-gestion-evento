<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     description="Rol del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Administrador"),
 *     @OA\Property(property="foto", type="string", format="url", example="http://localhost/storage/roles/admin.png")
 * )
 */
class RoleSchema {}