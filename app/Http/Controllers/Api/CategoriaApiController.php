<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Http\Responses\ApiResponse;
use App\Models\Categoria;
use App\Services\Catalogo\CategoriaCrudService;
use App\Services\Crud\PaginationParams;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Categorías',
    description: 'Endpoints para la gestión de categorías'
)]
final class CategoriaApiController extends Controller
{
    public function __construct(
        private readonly CategoriaCrudService $service,
    ) {
    }

    #[OA\Get(
        path: '/api/v1/categorias',
        operationId: 'listCategorias',
        tags: ['Categorías'],
        summary: 'Listar categorías',
        description: 'Obtiene una lista paginada de categorías',
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
        description: 'Lista de categorías',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Datos obtenidos exitosamente'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Categoria')
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
        $data = CategoriaResource::collection(collect($paginator->items()));

        return ApiResponse::paginated($data, $paginator);
    }

    #[OA\Post(
        path: '/api/v1/categorias',
        operationId: 'storeCategoria',
        tags: ['Categorías'],
        summary: 'Crear categoría',
        description: 'Crea una nueva categoría',
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/StoreCategoriaRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Categoría creada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Creado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Categoria')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function store(StoreCategoriaRequest $request)
    {
        $categoria = $this->service->create($request->validated());

        return ApiResponse::success(new CategoriaResource($categoria), 'Creado.', 201);
    }

    #[OA\Get(
        path: '/api/v1/categorias/{categoria}',
        operationId: 'showCategoria',
        tags: ['Categorías'],
        summary: 'Obtener categoría',
        description: 'Obtiene los detalles de una categoría específica',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'categoria',
        in: 'path',
        description: 'ID de la categoría',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Detalles de la categoría',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'data', ref: '#/components/schemas/Categoria')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Categoría no encontrada')]
    public function show(Categoria $categoria)
    {
        return ApiResponse::success(new CategoriaResource($categoria));
    }

    #[OA\Put(
        path: '/api/v1/categorias/{categoria}',
        operationId: 'updateCategoria',
        tags: ['Categorías'],
        summary: 'Actualizar categoría',
        description: 'Actualiza una categoría existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'categoria',
        in: 'path',
        description: 'ID de la categoría',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/UpdateCategoriaRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Categoría actualizada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Actualizado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Categoria')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Categoría no encontrada')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $categoria = $this->service->update($categoria, $request->validated());

        return ApiResponse::success(new CategoriaResource($categoria), 'Actualizado.');
    }

    #[OA\Delete(
        path: '/api/v1/categorias/{categoria}',
        operationId: 'destroyCategoria',
        tags: ['Categorías'],
        summary: 'Eliminar categoría',
        description: 'Elimina una categoría existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'categoria',
        in: 'path',
        description: 'ID de la categoría',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Categoría eliminada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Eliminado.')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Categoría no encontrada')]
    public function destroy(Categoria $categoria)
    {
        $this->service->delete($categoria);

        return ApiResponse::success(null, 'Eliminado.');
    }
}
