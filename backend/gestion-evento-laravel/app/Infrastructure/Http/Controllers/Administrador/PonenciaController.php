<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Application\UseCases\Ponencia\AllPonenciasUseCase;
use App\Application\UseCases\Ponencia\CreatePonenciaUseCase;
use App\Application\UseCases\Ponencia\DeletePonenciaUseCase;
use App\Application\UseCases\Ponencia\FindPonenciaUseCase;
use App\Application\UseCases\Ponencia\GetPonenciasPaginatedUseCase;
use App\Application\UseCases\Ponencia\SearchPonenciaUseCase;
use App\Application\UseCases\Ponencia\UpdatePonenciaUseCase;
use App\Domain\Entities\Ponencia;
use App\Infrastructure\Http\Requests\Ponencia\CreatePonenciaRequest;
use App\Infrastructure\Http\Requests\Ponencia\UpdatePonenciaRequest;
use App\Infrastructure\Http\Resources\PonenciaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RuntimeException;

class PonenciaController
{
    public function __construct(
        private AllPonenciasUseCase $allPonenciasUseCase,
        private CreatePonenciaUseCase $createPonenciaUseCase,
        private DeletePonenciaUseCase $deletePonenciaUseCase,
        private FindPonenciaUseCase $findPonenciaUseCase,
        private GetPonenciasPaginatedUseCase $getPonenciasPaginatedUseCase,
        private SearchPonenciaUseCase $searchPonenciaUseCase,
        private UpdatePonenciaUseCase $updatePonenciaUseCase
    ) {}

    /**
     * @OA\Get(
     *   path="/ponencias",
     *   summary="Listar ponencias",
     *   description="Devuelve todas las ponencias registradas",
     *   tags={"Ponencias"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Lista de ponencias",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Ponencia")
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
        $ponencias = $this->allPonenciasUseCase->execute();
        return response()->json(PonenciaResource::collection($ponencias), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/ponencias/{id}",
     *     summary="Obtener una ponencia por ID",
     *     description="Devuelve una ponencia específica por su ID",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la ponencia",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ponencia encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Ponencia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ponencia no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ponencia no encontrada")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $ponenciaEncontrada = $this->findPonenciaUseCase->execute($id);
            return response()->json(new PonenciaResource($ponenciaEncontrada), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/ponencias",
     *     summary="Crear una nueva ponencia",
     *     description="Crea una ponencia con todos sus campos y archivos opcionales",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre", "eventoId", "categoriaId", "ponente", "codigoQr"},
     *
     *                 @OA\Property(property="nombre", type="string", example="Impacto de la IA"),
     *                 @OA\Property(property="eventoId", type="integer", example=3),
     *                 @OA\Property(property="categoriaId", type="integer", example=2),
     *                 @OA\Property(property="ponente", type="string", example="Dr. Juan Pérez"),
     *                 @OA\Property(property="institucion", type="string", nullable=true, example="UPeU"),
     *
     *                 @OA\Property(
     *                     property="archivoPresentacion",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo PDF de la presentación"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Foto opcional de la ponencia"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="codigoQr",
     *                     type="string",
     *                     example="QR-ABC123"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Ponencia creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Ponencia")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(CreatePonenciaRequest $createPonenciaRequest): JsonResponse
    {
        $data = $createPonenciaRequest->validated();

        if ($createPonenciaRequest->hasFile('foto')) {
            $path = $createPonenciaRequest->file('foto')->store('images/ponencias', 'public');
            $data['foto'] = $path;
        }

        $nuevaPonencia = new Ponencia(
            id: null,
            nombre: $data['nombre'],
            eventoId: $data['eventoId'],
            categoriaId: $data['categoriaId'],
            ponente: $data['ponente'],
            institucion: $data['institucion'],
            archivoPresentacion: $data['archivoPresentacion'],
            foto: $data['foto'],
            codigoQr: $data['codigoQr']
        );

        $ponenciaCreada = $this->createPonenciaUseCase->execute(
            $nuevaPonencia
        );

        return response()->json(new PonenciaResource($ponenciaCreada), Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/ponencias/{id}",
     *     summary="Actualizar una ponencia",
     *     description="Permite actualizar los datos de una ponencia existente",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la ponencia a actualizar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nombre", type="string", example="Ponencia actualizada"),
     *                 @OA\Property(property="eventoId", type="integer", example=2),
     *                 @OA\Property(property="categoriaId", type="integer", example=1),
     *                 @OA\Property(property="ponente", type="string", example="Mg. Felipe Ramos"),
     *                 @OA\Property(property="institucion", type="string", example="UNSAAC"),
     *
     *                 @OA\Property(
     *                     property="archivoPresentacion",
     *                     type="string",
     *                     format="binary",
     *                     description="Nuevo PDF de la presentación"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Nueva foto opcional"
     *                 ),
     *
     *                 @OA\Property(property="codigoQr", type="string", example="QR-XYZ999")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Ponencia actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Ponencia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ponencia no encontrada"
     *     )
     * )
     */
    public function update(UpdatePonenciaRequest $updatePonenciaRequest, int $id): JsonResponse
    {
        try {
            $data = $updatePonenciaRequest->validated();

            if ($updatePonenciaRequest->hasFile('foto')) {
                $path = $updatePonenciaRequest->file('foto')->store('images/escuelas', 'public');
                $data['foto'] = $path;
            }

            $nuevaPonencia = new Ponencia(
                id: $id,
                nombre: $data['nombre'],
                eventoId: $data['eventoId'],
                categoriaId: $data['categoriaId'],
                ponente: $data['ponente'],
                institucion: $data['institucion'],
                archivoPresentacion: $data['archivoPresentacion'],
                foto: $data['foto'],
                codigoQr: $data['codigoQr']
            );

            $ponenciaCreada = $this->createPonenciaUseCase->execute(
                $nuevaPonencia
            );

            return response()->json(new PonenciaResource($ponenciaCreada), Response::HTTP_OK);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Delete(
     *     path="/ponencias/{id}",
     *     summary="Eliminar una ponencia",
     *     description="Elimina una ponencia por su ID",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la ponencia a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Ponencia eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ponencia no encontrada"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deletePonenciaUseCase->execute($id);

            return response()->json(
                ['message' => "Ponencia con id " . $id . " eliminado"],
                Response::HTTP_OK
            );
        } catch (RuntimeException $e) {
            return response()->json(
                ['message' => "Ponencia con id " . $id . " no encontrada"],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/ponencias/paginated",
     *     summary="Obtener ponencias paginadas",
     *     description="Devuelve una lista paginada de ponencias",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Página a visualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Cantidad por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de ponencias"
     *     )
     * )
     */
    public function paginated(Request $request): JsonResponse
    {
        $page    = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $paginator = $this->getPonenciasPaginatedUseCase->execute($page, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => PonenciaResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/ponencias/search",
     *     summary="Buscar ponencias",
     *     description="Permite buscar ponencias por nombre, ponente o institución",
     *     tags={"Ponencias"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string", example="IA")
     *     ),
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Resultados por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(response=200, description="Resultados obtenidos")
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $term = $request->input('q', '');
        $paginator = $this->searchPonenciaUseCase->execute($term, $perPage);

        return response()->json([
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'data'         => PonenciaResource::collection($paginator->items()),
        ], JsonResponse::HTTP_OK);
    }
}
