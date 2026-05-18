<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Http\Resources\MarcaResource;
use App\Http\Responses\ApiResponse;
use App\Models\Marca;
use App\Services\Catalogo\MarcaCrudService;
use App\Services\Crud\PaginationParams;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Marcas',
    description: 'Endpoints para la gestión de marcas'
)]
final class MarcaApiController extends Controller
{
    public function __construct(
        private readonly MarcaCrudService $service,
    ) {
    }

    #[OA\Get(
        path: '/api/v1/marcas',
        operationId: 'listMarcas',
        tags: ['Marcas'],
        summary: 'Listar marcas',
        description: 'Obtiene una lista paginada de marcas',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Número de página',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        description: 'Elementos por página (máximo 100)',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)
    )]
    #[OA\Parameter(
        name: 'search',
        in: 'query',
        description: 'Texto de búsqueda',
        required: false,
        schema: new OA\Schema(type: 'string', maxLength: 120)
    )]
    #[OA\Parameter(
        name: 'sort_by',
        in: 'query',
        description: 'Campo para ordenar',
        required: false,
        schema: new OA\Schema(type: 'string', maxLength: 50)
    )]
    #[OA\Parameter(
        name: 'sort_dir',
        in: 'query',
        description: 'Dirección de ordenamiento',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de marcas',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Datos obtenidos exitosamente'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Marca')
                ),
                new OA\Property(
                    property: 'meta',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'per_page', type: 'integer', example: 15),
                        new OA\Property(property: 'total', type: 'integer', example: 100),
                        new OA\Property(property: 'last_page', type: 'integer', example: 7)
                    ]
                )
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    public function index(IndexRequest $request)
    {
        $params = PaginationParams::fromArray($request->validated());
        $paginator = $this->service->paginate($params);
        $data = MarcaResource::collection(collect($paginator->items()));

        return ApiResponse::paginated($data, $paginator);
    }

    #[OA\Post(
        path: '/api/v1/marcas',
        operationId: 'storeMarca',
        tags: ['Marcas'],
        summary: 'Crear marca',
        description: 'Crea una nueva marca',
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/StoreMarcaRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Marca creada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Creado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Marca')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function store(StoreMarcaRequest $request)
    {
        $marca = $this->service->create($request->validated());

        return ApiResponse::success(new MarcaResource($marca), 'Creado.', 201);
    }

    #[OA\Get(
        path: '/api/v1/marcas/{marca}',
        operationId: 'showMarca',
        tags: ['Marcas'],
        summary: 'Obtener marca',
        description: 'Obtiene los detalles de una marca específica',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'marca',
        in: 'path',
        description: 'ID de la marca',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Detalles de la marca',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'data', ref: '#/components/schemas/Marca')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Marca no encontrada')]
    public function show(Marca $marca)
    {
        return ApiResponse::success(new MarcaResource($marca));
    }

    #[OA\Put(
        path: '/api/v1/marcas/{marca}',
        operationId: 'updateMarca',
        tags: ['Marcas'],
        summary: 'Actualizar marca',
        description: 'Actualiza una marca existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'marca',
        in: 'path',
        description: 'ID de la marca',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/UpdateMarcaRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Marca actualizada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Actualizado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Marca')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Marca no encontrada')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        $marca = $this->service->update($marca, $request->validated());

        return ApiResponse::success(new MarcaResource($marca), 'Actualizado.');
    }

    #[OA\Delete(
        path: '/api/v1/marcas/{marca}',
        operationId: 'destroyMarca',
        tags: ['Marcas'],
        summary: 'Eliminar marca',
        description: 'Elimina una marca existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'marca',
        in: 'path',
        description: 'ID de la marca',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Marca eliminada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Eliminado.')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Marca no encontrada')]
    public function destroy(Marca $marca)
    {
        $this->service->delete($marca);

        return ApiResponse::success(null, 'Eliminado.');
    }
}
