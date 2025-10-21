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
    public function index(Request $request)
    {
        $role = $request->query('role');
        $perPage = $request->query('per_page', 10);

        $users = $this->getPaginatedUsersUseCase->execute($role, $request->query('page', 1), $perPage);

        return UserResource::collection($users);
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
    public function search(Request $request)
    {
        $term = $request->query('term', '');
        $role = $request->query('role');
        $perPage = $request->query('per_page', 10);

        $users = $this->searchUsersUseCase->execute($term, $role, $perPage);

        return UserResource::collection($users);
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
    public function show(int $id)
    {
        $user = $this->findUserByIdUseCase->execute($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Registrar un nuevo usuario",
     *     description="Crea un nuevo usuario con su información personal, académica y de rol.",
     *     operationId="createUser",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para registrar un nuevo usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password", "escuela_id", "persona"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="escuela_id", type="integer", example=2),
     *             @OA\Property(property="role", type="string", example="alumno"),
     *
     *             @OA\Property(
     *                 property="persona",
     *                 type="object",
     *                 required={"nombres", "apellidos", "tipo_documento", "numero_documento", "correo_electronico", "fecha_nacimiento"},
     *                 @OA\Property(property="nombres", type="string", example="Juan"),
     *                 @OA\Property(property="apellidos", type="string", example="Pérez Gómez"),
     *                 @OA\Property(property="tipo_documento", type="string", example="DNI"),
     *                 @OA\Property(property="numero_documento", type="string", example="74125896"),
     *                 @OA\Property(property="telefono", type="string", nullable=true, example="987654321"),
     *                 @OA\Property(property="direccion", type="string", nullable=true, example="Av. Los Olivos 123"),
     *                 @OA\Property(property="correo_electronico", type="string", example="juan.perez@ejemplo.com"),
     *                 @OA\Property(property="foto_perfil", type="string", nullable=true, example="perfil.jpg"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1999-05-10")
     *             ),
     *
     *             @OA\Property(
     *                 property="alumno",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="codigo_universitario", type="string", example="20210045")
     *             ),
     *
     *             @OA\Property(
     *                 property="jurado",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="especialidad", type="string", example="Inteligencia Artificial")
     *             ),
     *
     *             @OA\Property(
     *                 property="ponente",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="biografia", type="string", example="Experto en gestión de proyectos tecnológicos")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
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
     *         response=401,
     *         description="No autorizado — requiere autenticación"
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $personaData = $request->input('persona');

        $persona = new Persona(
            id: null,
            nombres: $personaData['nombres'],
            apellidos: $personaData['apellidos'],
            tipoDocumento: $personaData['tipo_documento'],
            numeroDocumento: $personaData['numero_documento'],
            telefono: $personaData['telefono'] ?? null,
            direccion: $personaData['direccion'] ?? null,
            correoElectronico: $personaData['correo_electronico'],
            fotoPerfil: $personaData['foto_perfil'] ?? null,
            fechaNacimiento: new DateTime($personaData['fecha_nacimiento'])
        );

        $user = new User(
            id: null,
            email: $request->email,
            password: $request->password,
            escuelaId: $request->escuela_id,
            persona: $persona,
            alumno: $request->input('alumno') ? new Alumno(
                null,
                null,
                $request->input('alumno.codigo_universitario')
            ) : null,
            jurado: $request->input('jurado') ? new Jurado(
                null,
                null,
                $request->input('jurado.especialidad')
            ) : null,
            ponente: $request->input('ponente') ? new Ponente(
                null,
                $request->input('ponente.biografia'),
                null,
                null
            ) : null
        );

        $created = $this->createUserUseCase->execute($user, $request->role);

        return (new UserResource($created))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
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
     *         required=true,
     *         description="Datos actualizados del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "escuela_id", "persona"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario.actualizado@ejemplo.com"),
     *             @OA\Property(property="password", type="string", nullable=true, example="nuevaClave123"),
     *             @OA\Property(property="escuela_id", type="integer", example=3),
     *             @OA\Property(property="role", type="string", example="jurado"),
     *
     *             @OA\Property(
     *                 property="persona",
     *                 type="object",
     *                 required={"nombres", "apellidos", "tipo_documento", "numero_documento", "correo_electronico", "fecha_nacimiento"},
     *                 @OA\Property(property="nombres", type="string", example="María"),
     *                 @OA\Property(property="apellidos", type="string", example="Fernández López"),
     *                 @OA\Property(property="tipo_documento", type="string", example="DNI"),
     *                 @OA\Property(property="numero_documento", type="string", example="78945612"),
     *                 @OA\Property(property="telefono", type="string", nullable=true, example="999888777"),
     *                 @OA\Property(property="direccion", type="string", nullable=true, example="Calle Los Sauces 456"),
     *                 @OA\Property(property="correo_electronico", type="string", example="maria.fernandez@ejemplo.com"),
     *                 @OA\Property(property="foto_perfil", type="string", nullable=true, example="perfil_maria.jpg"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1998-02-14")
     *             ),
     *
     *             @OA\Property(
     *                 property="alumno",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="codigo_universitario", type="string", example="20224567")
     *             ),
     *
     *             @OA\Property(
     *                 property="jurado",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="especialidad", type="string", example="Ingeniería de Software")
     *             ),
     *
     *             @OA\Property(
     *                 property="ponente",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="biografia", type="string", example="Ponente en conferencias internacionales de tecnología educativa")
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
    public function update(UpdateUserRequest $request, int $id)
    {
        $personaData = $request->input('persona');

        $persona = new Persona(
            id: null,
            nombres: $personaData['nombres'],
            apellidos: $personaData['apellidos'],
            tipoDocumento: $personaData['tipo_documento'],
            numeroDocumento: $personaData['numero_documento'],
            telefono: $personaData['telefono'] ?? null,
            direccion: $personaData['direccion'] ?? null,
            correoElectronico: $personaData['correo_electronico'],
            fotoPerfil: $personaData['foto_perfil'] ?? null,
            fechaNacimiento: new DateTime($personaData['fecha_nacimiento'])
        );

        $user = new User(
            id: $id,
            email: $request->email,
            password: $request->password ?? '',
            escuelaId: $request->escuela_id,
            persona: $persona,
            alumno: $request->input('alumno') ? new Alumno(
                null,
                null,
                $request->input('alumno.codigo_universitario')
            ) : null,
            jurado: $request->input('jurado') ? new Jurado(
                null,
                null,
                $request->input('jurado.especialidad')
            ) : null,
            ponente: $request->input('ponente') ? new Ponente(
                null,
                $request->input('ponente.biografia'),
                null,
                null
            ) : null
        );

        $updated = $this->updateUserUseCase->execute($user, $request->role);

        return new UserResource($updated);
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
    public function destroy(int $id)
    {
        try {
            $this->deleteUserUseCase->execute($id);
            return response()->json(['message' => 'Usuario eliminado correctamente'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
    }
}
