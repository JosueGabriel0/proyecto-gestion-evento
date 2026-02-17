<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Application\UseCases\User\CreateUserUseCase;
use App\Application\UseCases\User\DeleteUserUseCase;
use App\Application\UseCases\User\FindUserByIdUseCase;
use App\Application\UseCases\User\GetAllUsersUseCase;
use App\Application\UseCases\User\GetPaginatedUsersUseCase;
use App\Application\UseCases\User\SearchUsersUseCase;
use App\Application\UseCases\User\UpdateUserUseCase;
use App\Domain\Entities\Alumno;
use App\Domain\Entities\Jurado;
use App\Domain\Entities\Persona;
use App\Domain\Entities\Ponente;
use App\Domain\Entities\User;
use App\Infrastructure\Http\Controllers\Controller;
use App\Infrastructure\Http\Requests\User\StoreUserRequest;
use App\Infrastructure\Http\Requests\User\UpdateUserRequest;
use App\Infrastructure\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class UserController extends Controller
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase,
        private FindUserByIdUseCase $findUserByIdUseCase,
        private GetAllUsersUseCase $getAllUsersUseCase,
        private GetPaginatedUsersUseCase $getPaginatedUsersUseCase,
        private SearchUsersUseCase $searchUsersUseCase,
        private UpdateUserUseCase $updateUserUseCase,
    ) {}

    /**
     * @OA\Get(
     *     path="/users/paginated",
     *     summary="Listar usuarios",
     *     description="Obtiene una lista paginada de usuarios, con opción de filtrar por rol.",
     *     operationId="getUsers",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filtra los usuarios por rol (por ejemplo: ROLE_ESTUDIANTE, ROLE_JURADO, ROLE_PONENTE)",
     *         required=false,
     *         @OA\Schema(type="string", example="ROLE_ESTUDIANTE")
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad de usuarios por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Listado de usuarios obtenido correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 description="Información de paginación",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=47)
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 description="Enlaces de navegación",
     *                 @OA\Property(property="first", type="string", example="http://api.test/api/users?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://api.test/api/users?page=5"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", example="http://api.test/api/users?page=2")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $role = $request->query('role');
        $perPage = $request->query('per_page', 10);

        $users = $this->getPaginatedUsersUseCase->execute($role, $request->query('page', 1), $perPage);

        return response()->json([
            'current_page' => $users->currentPage(),
            'per_page'     => $users->perPage(),
            'total'        => $users->total(),
            'data'         => UserResource::collection($users->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/users/search",
     *     summary="Buscar usuarios",
     *     description="Busca usuarios por nombre, apellidos o correo electrónico. Permite filtrar por rol y paginar los resultados.",
     *     operationId="searchUsers",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         description="Término de búsqueda (nombre, apellido o correo)",
     *         required=false,
     *         @OA\Schema(type="string", example="juan.perez@upeu.edu.pe")
     *     ),
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filtra usuarios por rol (por ejemplo: ROLE_ESTUDIANTE, ROLE_JURADO, ROLE_PONENTE)",
     *         required=false,
     *         @OA\Schema(type="string", example="ROLE_PONENTE")
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página de resultados",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad de usuarios por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Resultados de la búsqueda de usuarios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 description="Información de paginación",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=25)
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 description="Enlaces de navegación",
     *                 @OA\Property(property="first", type="string", example="http://api.test/api/users/search?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://api.test/api/users/search?page=3"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", example="http://api.test/api/users/search?page=2")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->query('term', '');
        $role = $request->query('role');
        $perPage = $request->query('per_page', 10);

        $users = $this->searchUsersUseCase->execute($term, $role, $perPage);

        return response()->json([
            'current_page' => $users->currentPage(),
            'per_page'     => $users->perPage(),
            'total'        => $users->total(),
            'data'         => UserResource::collection($users->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Obtener un usuario por ID",
     *     description="Devuelve los datos detallados de un usuario específico mediante su identificador único.",
     *     operationId="getUserById",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario que se desea consultar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado correctamente",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado — el token de autenticación no fue provisto o es inválido"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->findUserByIdUseCase->execute($id);
            return response()->json(new UserResource($user), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Crear información de un usuario",
     *     description="Crea los datos de un usuario existente, incluyendo su información personal, académica y rol.",
     *     operationId="createUser",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     * request="CreateUserRequest",
     *         required=true,
     *         description="Datos creados del usuario (multipart/form-data)",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={
     *                     "email", "escuela_id", 
     *                     "nombres", "apellidos", "tipo_documento", 
     *                     "numero_documento", "correo_electronico", "fecha_nacimiento"
     *                 },
     *
     *                 @OA\Property(property="email", type="string", format="email", example="usuario.actualizado@ejemplo.com"),
     *                 @OA\Property(property="password", type="string", nullable=true, example="nuevaClave123"),
     *                 @OA\Property(property="escuela_id", type="integer", example=3),
     *                 @OA\Property(property="role", type="string", example="jurado"),
     *
     *                 @OA\Property(property="nombres", type="string", example="María"),
     *                 @OA\Property(property="apellidos", type="string", example="Fernández López"),
     *                 @OA\Property(property="tipo_documento", type="string", example="DNI"),
     *                 @OA\Property(property="numero_documento", type="string", example="78945612"),
     *                 @OA\Property(property="telefono", type="string", nullable=true, example="999888777"),
     *                 @OA\Property(property="direccion", type="string", nullable=true, example="Calle Los Sauces 456"),
     *                 @OA\Property(property="pais", type="string", nullable=true, example="Peru"),
     *                 @OA\Property(property="religion", type="string", nullable=true, example="Adventista"),
     *                 @OA\Property(property="correo_electronico", type="string", example="maria.fernandez@ejemplo.com"),
     *                 @OA\Property(property="correo_institucional", type="string", example="maria.fernandez@ejemplo.com"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1998-02-14"),
     *
     *                 @OA\Property(
     *                     property="foto_perfil",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen del perfil (jpg, png, etc.)"
     *                 ),
     *
     *                 @OA\Property(property="codigo_universitario", type="string", nullable=true, example="20224567"),
     *                 @OA\Property(property="especialidad", type="string", nullable=true, example="Ingeniería de Software"),
     *                 @OA\Property(property="biografia", type="string", nullable=true, example="Ponente en conferencias internacionales de tecnología educativa"),
     *                 @OA\Property(
     *                  property="ponencia_id",
     *                  type="integer",
     *                  nullable=true,
     *                  example=5,
     *                  description="ID de la ponencia asignada al ponente"
     *                  )

     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos o incompletos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error en los datos enviados")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */

    public function store(StoreUserRequest $request): JsonResponse
    {
        // 1️⃣ Extraer campos básicos del usuario
        $email = $request->input('email');
        $password = $request->input('password');
        $escuelaId = $request->input('escuela_id');
        $role = $request->input('role');

        // 2️⃣ Procesar imagen de perfil (opcional)
        $fotoPerfil = null;
        if ($request->hasFile('foto_perfil')) {
            $fotoPerfil = $request->file('foto_perfil')->store('images/personas', 'public');
        }

        // 3️⃣ Crear entidad Persona (datos directos desde form-data)
        $persona = new Persona(
            id: null,
            nombres: $request->input('nombres'),
            apellidos: $request->input('apellidos'),
            tipoDocumento: $request->input('tipo_documento'),
            numeroDocumento: $request->input('numero_documento'),
            telefono: $request->input('telefono'),
            direccion: $request->input('direccion'),
            pais: $request->input('pais'),
            religion: $request->input('religion'),
            correoElectronico: $request->input('correo_electronico'),
            correoInstitucional: $request->input('correo_institucional'),
            fotoPerfil: $fotoPerfil,
            fechaNacimiento: new DateTime($request->input('fecha_nacimiento'))
        );

        // 4️⃣ Crear entidades opcionales (si el campo existe en el form-data)
        $alumno = $request->filled('codigo_universitario')
            ? new Alumno(
                id: null,
                userId: null,
                codigo_universitario: $request->input('codigo_universitario')
            )
            : null;

        $jurado = $request->filled('especialidad')
            ? new Jurado(
                id: null,
                userId: null,
                especialidad: $request->input('especialidad')
            )
            : null;

        $ponente = $request->filled('biografia') || $request->filled('ponencia_id')
            ? new Ponente(
                id: null,
                biografia: $request->input('biografia'),
                userId: null,
                ponenciaId: $request->input('ponencia_id') ? (int) $request->input('ponencia_id') : null
            )
            : null;

        // 5️⃣ Crear el usuario del dominio
        $user = new User(
            id: null,
            email: $email,
            password: $password,
            escuelaId: $escuelaId,
            persona: $persona,
            alumno: $alumno,
            jurado: $jurado,
            ponente: $ponente
        );

        // 6️⃣ Ejecutar caso de uso (aplica lógica de guardado y asignación de rol)
        $created = $this->createUserUseCase->execute($user, $role);

        // 7️⃣ Retornar respuesta en formato JSON (con UserResource)
        return response()->json(new UserResource($created), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}",
     *     summary="Actualizar información de un usuario",
     *     description="Actualiza los datos de un usuario existente, incluyendo su información personal, académica y rol.",
     *     operationId="updateUser",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *
     *     @OA\RequestBody(
     * request="UpdateUserRequest",
     *         required=true,
     *         description="Datos actualizados del usuario (multipart/form-data)",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={
     *                     "email", "escuela_id", 
     *                     "nombres", "apellidos", "tipo_documento", 
     *                     "numero_documento", "correo_electronico", "fecha_nacimiento"
     *                 },
     *
     *                 @OA\Property(property="email", type="string", format="email", example="usuario.actualizado@ejemplo.com"),
     *                 @OA\Property(property="password", type="string", nullable=true, example="nuevaClave123"),
     *                 @OA\Property(property="escuela_id", type="integer", example=3),
     *                 @OA\Property(property="role", type="string", example="jurado"),
     *
     *                 @OA\Property(property="nombres", type="string", example="María"),
     *                 @OA\Property(property="apellidos", type="string", example="Fernández López"),
     *                 @OA\Property(property="tipo_documento", type="string", example="DNI"),
     *                 @OA\Property(property="numero_documento", type="string", example="78945612"),
     *                 @OA\Property(property="telefono", type="string", nullable=true, example="999888777"),
     *                 @OA\Property(property="direccion", type="string", nullable=true, example="Calle Los Sauces 456"),
     *                 @OA\Property(property="pais", type="string", nullable=true, example="Peru"),
     *                 @OA\Property(property="religion", type="string", nullable=true, example="Adventista"),
     *                 @OA\Property(property="correo_electronico", type="string", example="maria.fernandez@ejemplo.com"),
     *                 @OA\Property(property="correo_institucional", type="string", example="maria.fernandez@ejemplo.com"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1998-02-14"),
     *
     *                 @OA\Property(
     *                     property="foto_perfil",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen del perfil (jpg, png, etc.)"
     *                 ),
     *
     *                 @OA\Property(property="codigo_universitario", type="string", nullable=true, example="20224567"),
     *                 @OA\Property(property="especialidad", type="string", nullable=true, example="Ingeniería de Software"),
     *                 @OA\Property(property="biografia", type="string", nullable=true, example="Ponente en conferencias internacionales de tecnología educativa"),
     * 
     *                 @OA\Property(
     *                  property="ponencia_id",
     *                  type="integer",
     *                  nullable=true,
     *                  example=5,
     *                  description="ID de la ponencia asignada al ponente"
     *                  )

     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos o incompletos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error en los datos enviados")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            // 1️⃣ Extraer datos simples
            $email = $request->input('email');
            $password = $request->input('password');
            $escuelaId = $request->input('escuela_id');
            $role = $request->input('role');

            // 2️⃣ Manejar la foto de perfil (si se envía nueva)
            $fotoPerfil = null;
            if ($request->hasFile('foto_perfil')) {
                $fotoPerfil = $request->file('foto_perfil')->store('images/personas', 'public');
            } else {
                // Si no se envía nueva foto, conservar la anterior
                $fotoPerfil = $request->input('foto_perfil_actual') ?? null;
            }

            // 3️⃣ Crear entidad Persona con datos planos del form-data
            $persona = new Persona(
                id: null,
                nombres: $request->input('nombres'),
                apellidos: $request->input('apellidos'),
                tipoDocumento: $request->input('tipo_documento'),
                numeroDocumento: $request->input('numero_documento'),
                telefono: $request->input('telefono'),
                direccion: $request->input('direccion'),
                pais: $request->input('pais'),
                religion: $request->input('religion'),
                correoElectronico: $request->input('correo_electronico'),
                correoInstitucional: $request->input('correo_institucional'),
                fotoPerfil: $fotoPerfil,
                fechaNacimiento: new DateTime($request->input('fecha_nacimiento'))
            );

            // 4️⃣ Relaciones opcionales
            $alumno = $request->filled('codigo_universitario')
                ? new Alumno(null, null, $request->input('codigo_universitario'))
                : null;

            $jurado = $request->filled('especialidad')
                ? new Jurado(null, null, $request->input('especialidad'))
                : null;

            $ponente = $request->filled('biografia') || $request->filled('ponencia_id')
                ? new Ponente(
                    id: null,
                    biografia: $request->input('biografia'),
                    userId: null,
                    ponenciaId: $request->input('ponencia_id') ? (int) $request->input('ponencia_id') : null
                )
                : null;

            // 5️⃣ Crear el usuario con los datos actualizados
            $user = new User(
                id: $id,
                email: $email,
                password: $password ?? '',
                escuelaId: $escuelaId,
                persona: $persona,
                alumno: $alumno,
                jurado: $jurado,
                ponente: $ponente
            );

            // 6️⃣ Ejecutar el caso de uso
            $updated = $this->updateUserUseCase->execute($user, $role);

            // 7️⃣ Devolver respuesta
            return response()->json(new UserResource($updated), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Eliminar un usuario",
     *     description="Elimina un usuario existente por su ID.",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No autorizado")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteUserUseCase->execute($id);
            return response()->json(['message' => 'Usuario eliminado correctamente'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }
}
