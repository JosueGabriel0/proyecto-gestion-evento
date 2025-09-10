<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Persona",
 *     type="object",
 *     title="Persona",
 *     description="Datos de la persona asociada a un usuario",
 *
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="userId", type="integer", nullable=true, example=1),
 *
 *     @OA\Property(property="nombres", type="string", example="Juan"),
 *     @OA\Property(property="apellidos", type="string", example="Pérez García"),
 *
 *     @OA\Property(property="tipoDocumento", type="string", example="DNI"),
 *     @OA\Property(property="numeroDocumento", type="string", example="12345678"),
 *
 *     @OA\Property(property="telefono", type="string", example="+51 987654321"),
 *     @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123"),
 *
 *     @OA\Property(property="correoElectronico", type="string", format="email", example="juan.perez@ejemplo.com"),
 *
 *     @OA\Property(
 *         property="fotoPerfil",
 *         type="string",
 *         format="url",
 *         nullable=true,
 *         example="http://localhost/storage/usuarios/juan.png"
 *     ),
 *
 *     @OA\Property(
 *         property="fechaNacimiento",
 *         type="string",
 *         format="date",
 *         example="1990-05-20"
 *     )
 * )
 */
class PersonaSchema {}