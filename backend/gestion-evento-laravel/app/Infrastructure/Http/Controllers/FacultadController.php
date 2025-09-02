<?php

namespace App\Infrastructure\Http\Controllers;

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
     *     path="/facultades",
     *     summary="Listar todas las facultades",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de facultades",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Facultad"))
     *     )
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
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facultad encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facultad no encontrada"
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
     *     summary="Crear una nueva Facultad con foto",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"nombre"},
     *                 @OA\Property(property="nombre", type="string", example="Supervisor"),
     *                 @OA\Property(
     *                     property="foto",
     *                     description="Imagen de la facultad",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Facultad creada",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     )
     * )
     */
    public function store(CreateFacultadRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/facultades', 'public');
            // Guardar la URL accesible públicamente
            $data['foto'] = asset('storage/' . $path);
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
     *     summary="Actualizar una facultad con foto",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string", example="Editor"),
     *                 @OA\Property(
     *                     property="foto",
     *                     description="Imagen de la facultad",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facultad actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Facultad")
     *     )
     * )
     */
    public function update(UpdateFacultadRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('images/facultades', 'public');
                // Guardar la URL accesible públicamente
                $data['foto'] = asset('storage/' . $path);
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
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Facultad eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facultad no encontrada"
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
