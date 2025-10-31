<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Infrastructure\Http\Controllers\Controller;

use App\Application\UseCases\Facultad\CreateFacultadUseCase;
use App\Application\UseCases\Facultad\DeleteFacultadUseCase;
use App\Application\UseCases\Facultad\GetAllFacultadesUseCase;
use App\Application\UseCases\Facultad\GetFacultadByIdUseCase;
use App\Application\UseCases\Facultad\GetFacultadesPaginatedUseCase;
use App\Application\UseCases\Facultad\SearchFacultadUseCase;
use App\Application\UseCases\Facultad\UpdateFacultadUseCase;
use App\Infrastructure\Http\Requests\Facultad\CreateFacultadRequest;
use App\Infrastructure\Http\Requests\Facultad\UpdateFacultadRequest;
use App\Infrastructure\Http\Resources\FacultadResource;
use App\Application\UseCases\Facultad\GetAllByFilialIdUseCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    public function __construct(
        private CreateFacultadUseCase $createFacultadUseCase,
        private UpdateFacultadUseCase $updateFacultadUseCase,
        private DeleteFacultadUseCase $deleteFacultadUseCase,
        private GetFacultadByIdUseCase $getFacultadByIdUseCase,
        private GetAllFacultadesUseCase $listFacultadesUseCase,
        private GetFacultadesPaginatedUseCase $getFacultadesPaginatedUseCase,
        private SearchFacultadUseCase $searchFacultadUseCase,
        private GetAllByFilialIdUseCase $getAllByFilialIdUseCase
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
     *     path="/facultades/filial/{filialId}",
     *     summary="Listar facultades por filial",
     *     description="Devuelve todas las facultades asociadas a una filial específica",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="filialId",
     *         in="path",
     *         required=true,
     *         description="ID de la filial",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de facultades por filial",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Facultad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron facultades para la filial indicada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No hay facultades para esta filial")
     *         )
     *     )
     * )
     */
    public function getByFilial(int $filialId): JsonResponse
    {
        $facultades = $this->getAllByFilialIdUseCase->execute($filialId);

        if (empty($facultades)) {
            return response()->json([
                'message' => 'No hay facultades para esta filial'
            ], Response::HTTP_NOT_FOUND);
        }

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
            $facultad = $this->getFacultadByIdUseCase->execute($id);
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

    /**
     * @OA\Get(
     *     path="/facultades/paginated",
     *     summary="Obtener facultades paginadas",
     *     description="Devuelve una lista paginada de facultades",
     *     operationId="getFacultadesPaginated",
     *     tags={"Facultades"},
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
     *         description="Lista paginada de facultades",
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

        $paginator = $this->getFacultadesPaginatedUseCase->execute($page, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => FacultadResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/facultades/search",
     *     summary="Buscar roles con paginación",
     *     description="Permite buscar facultades por nombre con soporte de paginación",
     *     tags={"Facultades"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda para filtrar facultades",
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
     *         description="Resultados de búsqueda de facultades con paginación",
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
        $paginator = $this->searchFacultadUseCase->execute($term, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => FacultadResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }
}
