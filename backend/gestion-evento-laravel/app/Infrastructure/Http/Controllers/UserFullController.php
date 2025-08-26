<?php

namespace App\Infrastructure\Http\Controllers;

use App\Http\Requests\StoreUserFullRequest;
use App\Http\Requests\UpdateUserFullRequest;
use App\Http\Resources\UserFullResource;
use App\Application\UseCases\CreateUserUseCase;
use App\Application\UseCases\GetUserUseCase;
use App\Application\UseCases\ListUsersUseCase;
use App\Application\UseCases\UpdateUserUseCase;
use App\Application\UseCases\DeleteUserUseCase;
use Illuminate\Http\JsonResponse;

class UserFullController extends Controller
{/*
    public function __construct(
        private CreateUserUseCase $createUser,
        private GetUserUseCase $getUser,
        private ListUsersUseCase $listUsers,
        private UpdateUserUseCase $updateUser,
        private DeleteUserUseCase $deleteUser
    ) {}

    public function index()
    {
        $users = $this->listUsers->execute();
        return UserFullResource::collection($users);
    }

    public function show(int $id)
    {
        $user = $this->getUser->execute($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return new UserFullResource($user);
    }

    public function store(StoreUserFullRequest $request)
    {
        $validated = $request->validated();

        $user = $this->createUser->execute(
            $validated['name'],
            $validated['email'],
            $validated['password'],
            $validated['role_id'] // 👉 ahora pasas el id, no buscas el Role en el Controller
        );

        return new UserFullResource($user);
    }

    public function update(UpdateUserFullRequest $request, int $id)
    {
        $validated = $request->validated();

        $user = $this->updateUser->execute(
            $id,
            $validated['name'] ?? null,
            $validated['email'] ?? null,
            $validated['password'] ?? null,
            $validated['role_id'] ?? null
        );

        return new UserFullResource($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->deleteUser->execute($id);

        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }*/
}