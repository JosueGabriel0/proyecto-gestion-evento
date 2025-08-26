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

class UserController extends Controller
{
    public function __construct(
        private CreateRoleUseCase $createRoleUseCase,
        private UpdateRoleUseCase $updateRoleUseCase,
        private DeleteRoleUseCase $deleteRoleUseCase,
        private GetRoleUseCase $getRoleUseCase,
        private ListRoleUseCase $listRolesUseCase
    ) {}

    // Listar todos los roles
    public function index(): JsonResponse
    {
        $roles = $this->listRolesUseCase->execute();
        return response()->json(RoleResource::collection($roles), Response::HTTP_OK);
    }

    // Obtener un rol por id
    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->getRoleUseCase->execute($id);
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    // Crear un nuevo rol
    public function store(RoleRequest $request): JsonResponse
    {
        $role = $this->createRoleUseCase->execute($request->nombre);
        return response()->json(new RoleResource($role), Response::HTTP_CREATED);
    }

    // Actualizar un rol existente
    public function update(RoleRequest $request, int $id): JsonResponse
    {
        try {
            $role = $this->updateRoleUseCase->execute($id, $request->nombre);
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    // Eliminar un rol
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