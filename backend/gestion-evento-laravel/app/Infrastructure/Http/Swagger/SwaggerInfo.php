<?php

namespace App\Infrastructure\Http\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Gestión de Eventos",
 *     version="1.0.0",
 *     description="Documentación de la API para el proyecto de gestión de eventos",
 *     @OA\Contact(
 *         email="soporte@ejemplo.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Introduce el token de acceso obtenido al iniciar sesión con Laravel Passport"
 * )
 */
class SwaggerInfo
{
    // Esta clase no necesita código, solo sirve para contener las anotaciones
}