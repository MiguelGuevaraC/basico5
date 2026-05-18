<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductoFotosRequest;
use App\Http\Resources\ProductoFotoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Producto;
use App\Models\ProductoFoto;
use App\Services\Productos\ProductoFotoService;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Fotos de Productos',
    description: 'Endpoints para la gestión de fotos de productos'
)]
final class ProductoFotoApiController extends Controller
{
    public function __construct(
        private readonly ProductoFotoService $service,
    ) {
    }

    #[OA\Post(
        path: '/api/v1/productos/{producto}/fotos',
        operationId: 'storeProductoFoto',
        tags: ['Fotos de Productos'],
        summary: 'Subir fotos de producto',
        description: 'Sube una o más fotos para un producto específico',
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
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                type: 'object',
                required: ['fotos'],
                properties: [
                    new OA\Property(
                        property: 'fotos',
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            format: 'binary'
                        ),
                        description: 'Archivos de imagen (máximo 10MB cada uno)'
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Fotos cargadas exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Fotos cargadas.'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/ProductoFoto')
                )
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Producto no encontrado')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function store(StoreProductoFotosRequest $request, Producto $producto)
    {
        $fotos = $this->service->store($producto, $request->file('fotos', []));

        return ApiResponse::success(ProductoFotoResource::collection($fotos), 'Fotos cargadas.', 201);
    }

    #[OA\Delete(
        path: '/api/v1/productos/{producto}/fotos/{foto}',
        operationId: 'destroyProductoFoto',
        tags: ['Fotos de Productos'],
        summary: 'Eliminar foto de producto',
        description: 'Elimina una foto específica de un producto',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'producto',
        in: 'path',
        description: 'ID del producto',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Parameter(
        name: 'foto',
        in: 'path',
        description: 'ID de la foto',
        required: true,
        schema: new OA\Schema(type: 'integer', format: 'int64')
    )]
    #[OA\Response(
        response: 200,
        description: 'Foto eliminada exitosamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Foto eliminada.')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'No autenticado')]
    #[OA\Response(response: 404, description: 'Producto o foto no encontrados')]
    public function destroy(Producto $producto, ProductoFoto $foto)
    {
        $this->service->delete($producto, $foto);

        return ApiResponse::success(null, 'Foto eliminada.');
    }
}
