<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Infrastructure\Http\Controllers\Controller;

use App\Application\UseCases\Role\CreateRoleUseCase;
use App\Application\UseCases\Role\UpdateRoleUseCase;
use App\Application\UseCases\Role\DeleteRoleUseCase;
use App\Application\UseCases\Role\GetRolesPaginatedUseCase;
use App\Application\UseCases\Role\GetRoleUseCase;
use App\Application\UseCases\Role\ListRoleUseCase;
use App\Application\UseCases\Role\SearchRolesUseCase;
use App\Infrastructure\Http\Requests\Role\RoleRequest;
use App\Infrastructure\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class RoleController extends Controller
{

    public function __construct(
        private CreateRoleUseCase $createRoleUseCase,
        private UpdateRoleUseCase $updateRoleUseCase,
        private DeleteRoleUseCase $deleteRoleUseCase,
        private GetRoleUseCase $getRoleUseCase,
        private ListRoleUseCase $listRolesUseCase,
        private GetRolesPaginatedUseCase $getRolesPaginated,
        private SearchRolesUseCase $searchRoles
    ) {}

    /**
     * @OA\Get(
     *   path="/roles",
     *   summary="Listar roles",
     *   description="Devuelve todos los roles disponibles",
     *   tags={"Roles"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de roles",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Role")
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
        $roles = $this->listRolesUseCase->execute();
        return response()->json(RoleResource::collection($roles), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/roles/{id}",
     *     summary="Obtener un rol por ID",
     *     description="Devuelve un rol específico por su ID",
     *     tags={"Roles"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rol no encontrado")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $role = $this->getRoleUseCase->execute((int) $id); // conviertes a entero aquí
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     summary="Crear un nuevo rol",
     *     description="Crea un rol con nombre y opcionalmente una foto",
     *     tags={"Roles"},
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
     *                     description="Nombre del rol",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen del rol (archivo opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
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
    public function store(RoleRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/roles', 'public');
            $data['foto'] = $path;
        }

        $role = $this->createRoleUseCase->execute($data['nombre'], $data['foto'] ?? null);

        return response()->json(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/roles/{id}",
     *     summary="Actualizar un rol existente",
     *     description="Permite actualizar el nombre de un rol y opcionalmente su foto",
     *     tags={"Roles"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a actualizar",
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
     *                     description="Nuevo nombre del rol",
     *                     example="Administrador actualizado"
     *                 ),
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Nueva imagen del rol (opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rol no encontrado")
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
    public function update(RoleRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            // Procesar nueva foto si viene en la request
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('images/roles', 'public');
                $data['foto'] = $path;
            }

            // Si no hay foto en el request, se mantiene la existente
            $role = $this->updateRoleUseCase->execute(
                $id,
                $data['nombre'],
                $data['foto'] ?? null
            );

            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/roles/{id}",
     *     summary="Eliminar un rol",
     *     description="Elimina un rol por su ID",
     *     tags={"Roles"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rol eliminado correctamente (sin contenido)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rol no encontrado")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteRoleUseCase->execute($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Get(
     *     path="/roles/paginated",
     *     summary="Obtener roles paginados",
     *     description="Devuelve una lista paginada de roles",
     *     operationId="getRolesPaginated",
     *     tags={"Roles"},
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
     *         description="Lista paginada de roles",
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

        $paginator = $this->getRolesPaginated->execute($page, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => RoleResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/roles/search",
     *     summary="Buscar roles con paginación",
     *     description="Permite buscar roles por nombre con soporte de paginación",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda para filtrar roles",
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
     *         description="Resultados de búsqueda de roles con paginación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Role")),
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
        $paginator = $this->searchRoles->execute($term, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => RoleResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }
}
