<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Infrastructure\Http\Controllers\Controller;
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
     *   path="/filiales",
     *   summary="Listar filiales",
     *   description="Devuelve todos las filiales disponibles",
     *   tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de filiales",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Filial")
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
        $filiales = $this->listFilialesUseCase->execute();
        return response()->json(FilialResource::collection($filiales), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/filiales/{id}",
     *     summary="Obtener una filial por ID",
     *     description="Devuelve una filial específica por su ID",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la filial",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filial encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filial no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Filial no encontrada")
     *         )
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
     *     summary="Crear una nueva filial",
     *     description="Crea una filial con nombre y opcionalmente una foto",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre"},
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     description="Nombre de la filial",
     *                     example="Example"
     * ),
     *                 @OA\Property(
     *                     property="direccion",
     *                     type="string",
     *                     description="Direccion",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string",
     *                     description="Telefono",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="email",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la filial (archivo opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Filial creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
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
    public function store(CreateFilialRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/filiales', 'public');
            $data['foto'] = $path;
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
     *     summary="Actualizar una filial existente",
     *     description="Permite actualizar el nombre de una filial y opcionalmente su foto",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la filial a actualizar",
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
     *                     description="Nuevo nombre de la filial",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="direccion",
     *                     type="string",
     *                     description="Direccion",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string",
     *                     description="Telefono",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="email",
     *                     example="Example"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Nueva imagen de la filial (opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filial actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Filial")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filial no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Filial no encontrada")
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
    public function update(UpdateFilialRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('images/filiales', 'public');
                $data['foto'] = $path;
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
     *     description="Elimina una filial por su ID",
     *     tags={"Filiales"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la filial a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Filial eliminado correctamente (sin contenido)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Filial no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Filial no encontrada")
     *         )
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
