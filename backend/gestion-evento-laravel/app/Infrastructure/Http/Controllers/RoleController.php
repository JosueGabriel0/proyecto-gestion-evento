<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Role\CreateRoleUseCase;
use App\Application\UseCases\Role\UpdateRoleUseCase;
use App\Application\UseCases\Role\DeleteRoleUseCase;
use App\Application\UseCases\Role\GetRoleUseCase;
use App\Application\UseCases\Role\ListRoleUseCase;
use App\Infrastructure\Http\Requests\RoleRequest;
use App\Infrastructure\Http\Resources\RoleResource;
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
        private ListRoleUseCase $listRolesUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/roles",
     *     summary="Listar todos los roles",
     *     tags={"Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role"))
     *     )
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
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->getRoleUseCase->execute($id);
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     summary="Crear un nuevo rol",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Supervisor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     )
     * )
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $role = $this->createRoleUseCase->execute($request->nombre);
        return response()->json(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="roles/{id}",
     *     summary="Actualizar un rol existente",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Editor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function update(RoleRequest $request, int $id): JsonResponse
    {
        try {
            $role = $this->updateRoleUseCase->execute($id, $request->nombre);
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * @OA\Delete(
     *     path="roles/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rol eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
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
}
