<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Infrastructure\Http\Controllers\Controller;

use App\Application\UseCases\Facultad\CreateFacultadUseCase;
use App\Application\UseCases\Facultad\DeleteFacultadUseCase;
use App\Application\UseCases\Facultad\GetAllFacultadesUseCase;
use App\Application\UseCases\Facultad\GetFacultadByIdUseCase;
use App\Application\UseCases\Facultad\UpdateFacultadUseCase;
use App\Infrastructure\Http\Requests\Facultad\CreateFacultadRequest;
use App\Infrastructure\Http\Requests\Facultad\UpdateFacultadRequest;
use App\Infrastructure\Http\Resources\FacultadResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class FacultadController extends Controller
{
    public function __construct(
        private CreateFacultadUseCase $createFacultadUseCase,
        private UpdateFacultadUseCase $updateFacultadUseCase,
        private DeleteFacultadUseCase $deleteFacultadUseCase,
        private GetFacultadByIdUseCase $getFacultadUseCase,
        private GetAllFacultadesUseCase $listFacultadesUseCase
    ) {}

    /**
     * @OA\Get(
     *   path="/facultades",
     *   summary="Listar facultades",
     *   description="Devuelve todos las facultades disponibles",
     *   tags={"Facultades"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de facultades",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Facultad")
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Error interno del servidor"
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $facultades = $this->listFacultadesUseCase->execute();
        return response()->json(FacultadResource::collection($facultades), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/facultades/{id}",
     *     summary="Obtener una facultad por ID",
     *     description="Devuelve una facultad específica por su ID",
     *     tags={"Facultades"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facultad encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facultad no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Facultad no encontrada")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $facultad = $this->getFacultadUseCase->execute($id);
            return response()->json(new FacultadResource($facultad), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/facultades",
     *     summary="Crear una nueva facultad",
     *     description="Crea una facultad con nombre y opcionalmente una foto",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre"},
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     description="Nombre de la facultad",
     *                     example="Ingeniería"
     *                 ),
     *                 @OA\Property(
     *                     property="codigo",
     *                     type="string",
     *                     description="Código de la facultad",
     *                     example="ING"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la facultad (archivo opcional)"
     *                 ),
     *                 @OA\Property(
     *                     property="filial_id",
     *                     type="integer",
     *                     description="ID de la filial asociada",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Facultad creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="El campo nombre es obligatorio."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(CreateFacultadRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/facultades', 'public');
            $data['foto'] = $path;
        }

        $facultad = $this->createFacultadUseCase->execute(
            $data['nombre'],
            $data['codigo'],
            $data['filial_id'],
            $data['foto'] ?? null
        );

        return response()->json(new FacultadResource($facultad), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/facultades/{id}",
     *     summary="Actualizar una facultad existente",
     *     description="Permite actualizar el nombre de una facultad y opcionalmente su foto",
     *     tags={"Facultades"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre"},
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     description="Nombre de la facultad",
     *                     example="Ingeniería"
     *                 ),
     *                 @OA\Property(
     *                     property="codigo",
     *                     type="string",
     *                     description="Código de la facultad",
     *                     example="ING"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la facultad (archivo opcional)"
     *                 ),
     *                 @OA\Property(
     *                     property="filial_id",
     *                     type="integer",
     *                     description="ID de la filial asociada",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facultad actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facultad no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Facultad no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="El campo nombre es obligatorio."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateFacultadRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('images/facultades', 'public');
                $data['foto'] = $path;
            }

            $facultad = $this->updateFacultadUseCase->execute(
                $id,
                $data['nombre'] ?? null,
                $data['codigo'] ?? null,
                $data['filial_id'] ?? null,
                $data['foto'] ?? null
            );

            return response()->json(new FacultadResource($facultad), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    
    /**
     * @OA\Delete(
     *     path="/facultades/{id}",
     *     summary="Eliminar una facultad",
     *     description="Elimina una facultad por su ID",
     *     tags={"Facultades"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Facultad eliminada correctamente (sin contenido)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facultad no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Facultad no encontrada")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteFacultadUseCase->execute($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
