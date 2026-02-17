<?php

namespace App\Infrastructure\Http\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Ponencia",
 *     type="object",
 *     title="Ponencia",
 *     description="Representación de una ponencia dentro del sistema",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         example="Impacto de la Inteligencia Artificial en la Educación Superior"
 *     ),
 *
 *     @OA\Property(
 *         property="eventoId",
 *         type="integer",
 *         description="ID del evento asociado",
 *         example=3
 *     ),
 *
 *     @OA\Property(
 *         property="categoriaId",
 *         type="integer",
 *         description="ID de la categoría de la ponencia",
 *         example=2
 *     ),
 *
 *     @OA\Property(
 *         property="ponente",
 *         type="string",
 *         description="Nombre del ponente",
 *         example="Dr. Juan Pérez"
 *     ),
 *
 *     @OA\Property(
 *         property="institucion",
 *         type="string",
 *         nullable=true,
 *         description="Institución del ponente",
 *         example="Universidad Peruana Unión"
 *     ),
 *
 *     @OA\Property(
 *         property="archivoPresentacion",
 *         type="string",
 *         nullable=true,
 *         description="Ruta del archivo PDF de la presentación",
 *         format="url",
 *         example="http://localhost/storage/ponencias/presentacion_123.pdf"
 *     ),
 *
 *     @OA\Property(
 *         property="foto",
 *         type="string",
 *         nullable=true,
 *         description="Ruta de la foto relacionada a la ponencia",
 *         format="url",
 *         example="http://localhost/storage/ponencias/foto_ponencia_123.png"
 *     ),
 *
 *     @OA\Property(
 *         property="codigoQr",
 *         type="string",
 *         description="Código QR generado para la ponencia",
 *         example="QR-ABC123456"
 *     )
 * )
 */
class PonenciaSchema {}