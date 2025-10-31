<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Application\UseCases\Escuela\CreateEscuelaUseCase;
use App\Application\UseCases\Escuela\DeleteEscuelaUseCase;
use App\Application\UseCases\Escuela\GetAllEscuelasByFacultadIdUseCase;
use App\Application\UseCases\Escuela\GetAllEscuelasUseCase;
use App\Application\UseCases\Escuela\GetEscuelaByIdUseCase;
use App\Application\UseCases\Escuela\GetEscuelasPaginatedUseCase;
use App\Application\UseCases\Escuela\SearchEscuelaUseCase;
use App\Application\UseCases\Escuela\UpdateEscuelaUseCase;
use App\Infrastructure\Http\Requests\Escuela\CreateEscuelaRequest;
use App\Infrastructure\Http\Requests\Escuela\UpdateEscuelaRequest;
use App\Infrastructure\Http\Resources\EscuelaResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Illuminate\Http\Request;

class EscuelaController
{
    public function __construct(
        private CreateEscuelaUseCase $createEscuelaUseCase,
        private DeleteEscuelaUseCase $deleteEscuelaUseCase,
        private GetAllEscuelasUseCase $getAllEscuelasUseCase,
        private GetEscuelaByIdUseCase $getEscuelaByIdUseCase,
        private UpdateEscuelaUseCase $updateEscuelaUseCase,
        private GetEscuelasPaginatedUseCase $getEscuelasPaginatedUseCase,
        private SearchEscuelaUseCase $searchEscuelaUseCase,
        private GetAllEscuelasByFacultadIdUseCase $getAllEscuelasByFacultadIdUseCase
    ) {}

    /**
     * @OA\Get(
     *   path="/escuelas",
     *   summary="Listar escuelas",
     *   description="Devuelve todas las escuelas disponibles",
     *   tags={"Escuelas"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de escuelas",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Escuela")
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
        $escuelas = $this->getAllEscuelasUseCase->execute();
        return response()->json(EscuelaResource::collection($escuelas), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/escuelas/facultad/{facultadId}",
     *     summary="Listar escuelas por facultad",
     *     description="Devuelve todas las escuelas asociadas a una facultad específica",
     *     tags={"Escuelas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="facultadId",
     *         in="path",
     *         required=true,
     *         description="ID de la facultad",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de escuelas por facultad",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Escuela")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron escuelas para la facultad indicada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No hay facultades para esta filial")
     *         )
     *     )
     * )
     */
    public function getByFacultad(int $facultadId): JsonResponse
    {
        $escuelas = $this->getAllEscuelasByFacultadIdUseCase->execute($facultadId);

        if (empty($escuelas)) {
            return response()->json([
                'message' => 'No hay escuelas para esta facultad'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(EscuelaResource::collection($escuelas), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/escuelas/{id}",
     *     summary="Obtener una escuela por ID",
     *     description="Devuelve una escuela específica por su ID",
     *     tags={"Escuelas"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la escuela",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Escuela encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Escuela")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escuela no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Escuela no encontrada")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $escuelaEncontrada = $this->getEscuelaByIdUseCase->execute($id);
            return response()->json(new EscuelaResource($escuelaEncontrada), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/escuelas",
     *     summary="Crear una nueva escuela",
     *     description="Crea una escuela con nombre y opcionalmente una foto",
     *     tags={"Escuelas"},
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
     *                     description="Nombre de la escuela",
     *                     example="Ingeniería"
     *                 ),
     *                 required={"codigo"},
     *                 @OA\Property(
     *                     property="codigo",
     *                     type="string",
     *                     description="Código de la escuela",
     *                     example="ING"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la escuela (archivo opcional)"
     *                 ),
     *                 required={"facultad_id"},
     *                 @OA\Property(
     *                     property="facultad_id",
     *                     type="integer",
     *                     description="ID de la facultad asociada",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Escuela creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Escuela")
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
    public function store(CreateEscuelaRequest $createEscuelaRequest): JsonResponse
    {
        $data = $createEscuelaRequest->validated();

        if ($createEscuelaRequest->hasFile('foto')) {
            $path = $createEscuelaRequest->file('foto')->store('images/escuelas', 'public');
            $data['foto'] = $path;
        }

        $nuevaEscuela = $this->createEscuelaUseCase->execute(
            $data['nombre'],
            $data['codigo'],
            $data['facultad_id'],
            $data['foto'] ?? null
        );

        return response()->json(new EscuelaResource($nuevaEscuela), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/escuelas/{id}",
     *     summary="Actualizar una escuela existente",
     *     description="Permite actualizar el nombre de una escuela y opcionalmente su foto",
     *     tags={"Escuelas"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la escuela a actualizar",
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
     *                     description="Nombre de la escuela",
     *                     example="Ingeniería"
     *                 ),
     *                 required={"codigo"},
     *                 @OA\Property(
     *                     property="codigo",
     *                     type="string",
     *                     description="Código de la escuela",
     *                     example="ING"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de la escuela (archivo opcional)"
     *                 ),
     *                 required={"facultad_id"},
     *                 @OA\Property(
     *                     property="facultad_id",
     *                     type="integer",
     *                     description="ID de la facultad asociada",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Escuela actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Escuela")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escuela no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Escuela no encontrada")
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
    public function update(UpdateEscuelaRequest $updateEscuelaRequest, int $id): JsonResponse
    {
        try {
            $data = $updateEscuelaRequest->validated();

            if ($updateEscuelaRequest->hasFile('foto')) {
                $path = $updateEscuelaRequest->file('foto')->store('images/escuelas', 'public');
                $data['foto'] = $path;
            }

            $escuelaEncontrada = $this->updateEscuelaUseCase->execute(
                $id,
                $data['nombre'],
                $data['codigo'],
                $data['facultad_id'],
                $data['foto'] ?? null,
            );

            return response()->json(new EscuelaResource($escuelaEncontrada), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/escuelas/{id}",
     *     summary="Eliminar una escuela",
     *     description="Elimina una escuela por su ID",
     *     tags={"Escuelas"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la escuela a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Escuela eliminada correctamente (sin contenido)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escuela no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Escuela no encontrada")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteEscuelaUseCase->execute($id);

            return response()->json(
                ['message' => "Rol con id " . $id . " eliminado"],
                Response::HTTP_OK
            );
        } catch (RuntimeException $e) {
            return response()->json(
                ['message' => "Rol con id " . $id . " no encontrado"],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/escuelas/paginated",
     *     summary="Obtener escuelas paginadas",
     *     description="Devuelve una lista paginada de escuelas",
     *     operationId="getEscuelasPaginated",
     *     tags={"Escuelas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página a obtener",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de escuelas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=57),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Administrador"),
     *                     @OA\Property(property="foto", type="string", nullable=true, example="https://example.com/foto.png")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parámetros inválidos"
     *     )
     * )
     */
    public function paginated(Request $request): JsonResponse
    {
        $page    = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $paginator = $this->getEscuelasPaginatedUseCase->execute($page, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => EscuelaResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/escuelas/search",
     *     summary="Buscar escuelas con paginación",
     *     description="Permite buscar escuelas por nombre con soporte de paginación",
     *     tags={"Escuelas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda para filtrar escuelas",
     *         @OA\Schema(type="string", example="Administrador")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Cantidad de resultados por página (por defecto 10)",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultados de búsqueda de escuelas con paginación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Facultad")),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $term = $request->input('q', '');
        $paginator = $this->searchEscuelaUseCase->execute($term, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => EscuelaResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }
}
