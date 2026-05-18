<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Producto;
use App\Services\Catalogo\ProductoCrudService;
use App\Services\Crud\PaginationParams;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Productos',
    description: 'Endpoints para la gestión de productos'
)]
final class ProductoApiController extends Controller
{
    public function __construct(
        private readonly ProductoCrudService $service,
    ) {
    }

    #[OA\Get(
        path: '/api/v1/productos',
        operationId: 'listProductos',
        tags: ['Productos'],
        summary: 'Listar productos',
        description: 'Obtiene una lista paginada de productos',
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
        description: 'Lista de productos',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Datos obtenidos exitosamente'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Producto')
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
        $data = ProductoResource::collection(collect($paginator->items()));

        return ApiResponse::paginated($data, $paginator);
    }

    #[OA\Post(
        path: '/api/v1/productos',
        operationId: 'storeProducto',
        tags: ['Productos'],
        summary: 'Crear producto',
        description: 'Crea un nuevo producto',
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/StoreProductoRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Producto creado exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Creado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Producto')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function store(StoreProductoRequest $request)
    {
        $producto = $this->service->create($request->validated());

        return ApiResponse::success(new ProductoResource($producto), 'Creado.', 201);
    }

    #[OA\Get(
        path: '/api/v1/productos/{producto}',
        operationId: 'showProducto',
        tags: ['Productos'],
        summary: 'Obtener producto',
        description: 'Obtiene los detalles de un producto específico con su categoría, marca y fotos',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'producto',
        in: 'path',
        description: 'ID del producto',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Detalles del producto',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'data', ref: '#/components/schemas/Producto')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Producto no encontrado')]
    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'marca', 'fotos']);

        return ApiResponse::success(new ProductoResource($producto));
    }

    #[OA\Put(
        path: '/api/v1/productos/{producto}',
        operationId: 'updateProducto',
        tags: ['Productos'],
        summary: 'Actualizar producto',
        description: 'Actualiza un producto existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'producto',
        in: 'path',
        description: 'ID del producto',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/UpdateProductoRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Producto actualizado exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Actualizado.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/Producto')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Producto no encontrado')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $producto = $this->service->update($producto, $request->validated());

        return ApiResponse::success(new ProductoResource($producto), 'Actualizado.');
    }

    #[OA\Delete(
        path: '/api/v1/productos/{producto}',
        operationId: 'destroyProducto',
        tags: ['Productos'],
        summary: 'Eliminar producto',
        description: 'Elimina un producto existente',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'producto',
        in: 'path',
        description: 'ID del producto',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Producto eliminado exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Eliminado.')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Producto no encontrado')]
    public function destroy(Producto $producto)
    {
        $this->service->delete($producto);

        return ApiResponse::success(null, 'Eliminado.');
    }
}
