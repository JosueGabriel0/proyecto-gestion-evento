<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Application\UseCases\User\CreateUserWithPersonaCommand;
use App\Application\UseCases\User\UpdateUserWithPersonaCommand;
use App\Application\UseCases\User\CreateUserWithPersonaUseCase;
use App\Application\UseCases\User\DeleteUserUseCase;
use App\Application\UseCases\User\FindUserByIdUseCase;
use App\Application\UseCases\User\ListAllUsuarioWithPersonaUseCase;
use App\Application\UseCases\User\UpdateUserWithPersonaUseCase;
use App\Infrastructure\Http\Controllers\Controller;
use App\Infrastructure\Http\Requests\User\StoreUserWithPersonaRequest;
use App\Infrastructure\Http\Requests\User\UpdateUserWithPersonaRequest;
use App\Infrastructure\Http\Resources\UserWithPersonaResource;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPersonaController extends Controller
{
    /**
     * @OA\Get(
     *   path="/usuarios",
     *   summary="Listar usuarios",
     *   description="Devuelve todos los usuarios disponibles",
     *   tags={"Usuarios"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de usuarios",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Usuario")
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Error interno del servidor"
     *   )
     * )
     */
    public function index(Request $request, ListAllUsuarioWithPersonaUseCase $useCase): JsonResponse
    {
        $perPage   = min((int) $request->query('per_page', 15), 100);
        $sortBy    = $request->query('sort_by', 'id');
        $sortDir   = strtolower($request->query('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $result = $useCase->execute([
            'per_page'   => $perPage,
            'sort_by'    => $sortBy,
            'sort_dir'   => $sortDir,
            'q'          => $request->query('q'),
            'role_id'    => $request->query('role_id'),
            'escuela_id' => $request->query('escuela_id'),
        ]);

        return response()->json(UserWithPersonaResource::collection($result));
    }

    /**
     * @OA\Post(
     *     path="/usuarios",
     *     summary="Crear un nuevo usuario",
     *     description="Crea un usuario con nombre y opcionalmente una foto",
     *     tags={"Usuarios"},
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                     example="Administrador@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Password",
     *                     example="administrador123"
     *                 ),
     *                 @OA\Property(
     *                     property="escuela_id",
     *                     type="integer",
     *                     description="Escuela_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="integer",
     *                     description="Role_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="nombres",
     *                     type="string",
     *                     description="Nombres",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="apellidos",
     *                     type="string",
     *                     description="Apellidos",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo_documento",
     *                     type="string",
     *                     description="Tipo_documento",
     *                     example="DNI"
     *                 ),
     *                 @OA\Property(
     *                     property="numero_documento",
     *                     type="string",
     *                     description="numero_documento",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string",
     *                     description="Telefono",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="direccion",
     *                     type="string",
     *                     description="Direccion",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="correo_electronico",
     *                     type="string",
     *                     description="Correo_electronico",
     *                     example="1234567@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="fecha_nacimiento",
     *                     type="string",
     *                     description="Fecha_nacimiento",
     *                     example="2000-07-10"
     *                 ),
     *                 @OA\Property(
     *                     property="codigo_universitario",
     *                     type="string",
     *                     description="Codigo_universitario",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="carrera",
     *                     type="string",
     *                     description="Carrera",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="ciclo",
     *                     type="string",
     *                     description="Ciclo",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="biografia",
     *                     type="string",
     *                     description="Biografia",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="ponencia_id",
     *                     type="integer",
     *                     description="Ponencia_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="especialidad",
     *                     type="string",
     *                     description="Especialidad",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="foto_perfil",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen del usuario (archivo opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
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
    public function store(StoreUserWithPersonaRequest $request, CreateUserWithPersonaUseCase $useCase): JsonResponse
    {
        try {
            $cmd = $request->toCommand();
            $domainUser = $useCase->execute($cmd);

            return (new UserWithPersonaResource($domainUser))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el usuario',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/usuarios/{id}",
     *     summary="Obtener un usuario por ID",
     *     description="Devuelve un usuario específico por su ID",
     *     tags={"Usuarios"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function show(int $id, FindUserByIdUseCase $useCase): JsonResponse|UserWithPersonaResource
    {
        try {
            $domainUser = $useCase->execute($id);
            return new UserWithPersonaResource($domainUser);
        } catch (DomainException | ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/usuarios/{id}",
     *     summary="Actualizar un usuario existente",
     *     description="Permite actualizar el nombre de un usuario y opcionalmente su foto",
     *     tags={"Usuarios"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                     example="Administrador@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Password",
     *                     example="administrador123"
     *                 ),
     *                 @OA\Property(
     *                     property="escuela_id",
     *                     type="integer",
     *                     description="Escuela_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="integer",
     *                     description="Role_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="nombres",
     *                     type="string",
     *                     description="Nombres",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="apellidos",
     *                     type="string",
     *                     description="Apellidos",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo_documento",
     *                     type="string",
     *                     description="Tipo_documento",
     *                     example="DNI"
     *                 ),
     *                 @OA\Property(
     *                     property="numero_documento",
     *                     type="string",
     *                     description="numero_documento",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string",
     *                     description="Telefono",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="direccion",
     *                     type="string",
     *                     description="Direccion",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="correo_electronico",
     *                     type="string",
     *                     description="Correo_electronico",
     *                     example="1234567@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="fecha_nacimiento",
     *                     type="string",
     *                     description="Fecha_nacimiento",
     *                     example="2000-07-10"
     *                 ),
     *                 @OA\Property(
     *                     property="codigo_universitario",
     *                     type="string",
     *                     description="Codigo_universitario",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="carrera",
     *                     type="string",
     *                     description="Carrera",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="ciclo",
     *                     type="string",
     *                     description="Ciclo",
     *                     example="1234567"
     *                 ),
     *                 @OA\Property(
     *                     property="biografia",
     *                     type="string",
     *                     description="Biografia",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="ponencia_id",
     *                     type="integer",
     *                     description="Ponencia_id",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                     property="especialidad",
     *                     type="string",
     *                     description="Especialidad",
     *                     example="Administrador"
     *                 ),
     *                 @OA\Property(
     *                     property="foto_perfil",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen del usuario (archivo opcional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Usuario")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
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
    public function update(UpdateUserWithPersonaRequest $request, int $id, UpdateUserWithPersonaUseCase $useCase)
    {
        try {
            $cmd = $request->toCommand($id); // 🚀 aquí se pasa el id
            $domainUser = $useCase->execute($cmd);

            return new UserWithPersonaResource($domainUser);
        } catch (DomainException | ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/usuarios/{id}",
     *     summary="Eliminar un usuario",
     *     description="Elimina un usuario por su ID",
     *     tags={"Usuarios"},
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado correctamente (sin contenido)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function destroy(int $id, DeleteUserUseCase $useCase): JsonResponse
    {
        try {
            $useCase->execute($id);
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (DomainException | ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
        }
    }
}
