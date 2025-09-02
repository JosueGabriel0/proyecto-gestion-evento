<?php

namespace App\Infrastructure\Http\Controllers;


namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Filial\AllFilialesUseCase;
use App\Application\UseCases\Filial\CreateFilialUseCase;
use App\Application\UseCases\Filial\DeleteFilialUseCase;
use App\Application\UseCases\Filial\FindFilialUseCase;
use App\Application\UseCases\Filial\UpdateFilialUseCase;
use App\Infrastructure\Http\Requests\Filial\CreateFilialRequest;
use App\Infrastructure\Http\Requests\Filial\UpdateFilialRequest;
use App\Infrastructure\Http\Resources\FilialResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class FilialController extends Controller
{
    public function __construct(
        private CreateFilialUseCase $createFilialUseCase,
        private UpdateFilialUseCase $updateFilialUseCase,
        private DeleteFilialUseCase $deleteFilialUseCase,
        private FindFilialUseCase $getFilialUseCase,
        private AllFilialesUseCase $listFilialesUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/filiales",
     *     summary="Listar todas las filiales",
     *     tags={"Filiales"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de filiales",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Filial"))
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        $filiales = $this->listFilialesUseCase->execute();
        return response()->json(FilialResource::collection($filiales), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/filiales/{id}",
     *     summary="Obtener una filial por ID",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la filial",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filial encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filial no encontrada"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $filial = $this->getFilialUseCase->execute($id);
            return response()->json(new FilialResource($filial), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/filiales",
     *     summary="Crear una nueva Filial con foto",
     *     tags={"Filiales"},
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
     *                     description="Imagen de la filial",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Filial creada",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
     *     )
     * )
     */
    public function store(CreateFilialRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/facultades', 'public');
            // Guardar la URL accesible públicamente
            $data['foto'] = asset('storage/' . $path);
        }


        $filial = $this->createFilialUseCase->execute(
            $data['nombre'],
            $data['direccion'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['foto'] ?? null
        );

        return response()->json(new FilialResource($filial), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/filiales/{id}",
     *     summary="Actualizar una filial con foto",
     *     tags={"Filiales"},
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
     *                     description="Imagen de la filial",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filial actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
     *     )
     * )
     */
    public function update(UpdateFilialRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('images/facultades', 'public');
                // Guardar la URL accesible públicamente
                $data['foto'] = asset('storage/' . $path);
            }

            $filial = $this->updateFilialUseCase->execute(
                $id,
                $data['nombre'],
                $data['direccion'] ?? null,
                $data['telefono'] ?? null,
                $data['email'] ?? null,
                $data['foto'] ?? null
            );

            return response()->json(new FilialResource($filial), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/filiales/{id}",
     *     summary="Eliminar una filial",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la filial",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Filial eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filial no encontrada"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteFilialUseCase->execute($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
