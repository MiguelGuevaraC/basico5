<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API de Productos - Básico 5',
    description: 'Documentación de la API REST para la gestión de productos, categorías, marcas y fotos',
    contact: new OA\Contact(email: 'soporte@ejemplo.com')
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'Servidor de desarrollo'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Ingrese el token JWT con el prefijo "Bearer " (ejemplo: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...)'
)]
#[OA\Schema(
    schema: 'ApiResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string', example: 'Operación exitosa'),
        new OA\Property(property: 'data', type: 'object', nullable: true)
    ]
)]
#[OA\Schema(
    schema: 'ApiResponsePaginated',
    type: 'object',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string', example: 'Datos obtenidos exitosamente'),
        new OA\Property(property: 'data', type: 'array', items: new OA\Items()),
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
)]
#[OA\Schema(
    schema: 'Categoria',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Electrónica'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z')
    ]
)]
#[OA\Schema(
    schema: 'Marca',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Apple'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z')
    ]
)]
#[OA\Schema(
    schema: 'Producto',
    type: 'object',
    required: ['nombre', 'precio'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'nombre', type: 'string', maxLength: 255, example: 'iPhone 15'),
        new OA\Property(property: 'precio', type: 'number', format: 'decimal', example: 999.99),
        new OA\Property(property: 'stock', type: 'integer', example: 50, nullable: true),
        new OA\Property(property: 'categoria_id', type: 'integer', format: 'int64', example: 1, nullable: true),
        new OA\Property(property: 'marca_id', type: 'integer', format: 'int64', example: 1, nullable: true),
        new OA\Property(property: 'categoria', ref: '#/components/schemas/Categoria', nullable: true),
        new OA\Property(property: 'marca', ref: '#/components/schemas/Marca', nullable: true),
        new OA\Property(property: 'fotos', type: 'array', items: new OA\Items(ref: '#/components/schemas/ProductoFoto')),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z')
    ]
)]
#[OA\Schema(
    schema: 'ProductoFoto',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'producto_id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'http://localhost/storage/fotos/abc123.jpg'),
        new OA\Property(property: 'ruta', type: 'string', example: 'fotos/abc123.jpg'),
        new OA\Property(property: 'nombre_original', type: 'string', example: 'foto.jpg'),
        new OA\Property(property: 'mime_type', type: 'string', example: 'image/jpeg'),
        new OA\Property(property: 'tamano_bytes', type: 'integer', example: 102400),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z')
    ]
)]
#[OA\Schema(
    schema: 'LoginRequest',
    type: 'object',
    required: ['username', 'password'],
    properties: [
        new OA\Property(property: 'username', type: 'string', example: 'administrador'),
        new OA\Property(property: 'password', type: 'string', minLength: 6, example: 'admin123')
    ]
)]
#[OA\Schema(
    schema: 'LoginResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'),
        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
        new OA\Property(property: 'expires_in', type: 'integer', example: 3600)
    ]
)]
#[OA\Schema(
    schema: 'StoreCategoriaRequest',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Electrónica')
    ]
)]
#[OA\Schema(
    schema: 'UpdateCategoriaRequest',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Electrónica Actualizada')
    ]
)]
#[OA\Schema(
    schema: 'StoreMarcaRequest',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Apple')
    ]
)]
#[OA\Schema(
    schema: 'UpdateMarcaRequest',
    type: 'object',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 120, example: 'Apple Actualizada')
    ]
)]
#[OA\Schema(
    schema: 'StoreProductoRequest',
    type: 'object',
    required: ['nombre', 'precio'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 255, example: 'iPhone 15'),
        new OA\Property(property: 'precio', type: 'number', format: 'decimal', minimum: 0, example: 999.99),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 50, nullable: true),
        new OA\Property(property: 'categoria_id', type: 'integer', format: 'int64', example: 1, nullable: true),
        new OA\Property(property: 'marca_id', type: 'integer', format: 'int64', example: 1, nullable: true)
    ]
)]
#[OA\Schema(
    schema: 'UpdateProductoRequest',
    type: 'object',
    required: ['nombre', 'precio'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', maxLength: 255, example: 'iPhone 15 Pro'),
        new OA\Property(property: 'precio', type: 'number', format: 'decimal', minimum: 0, example: 1199.99),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 30, nullable: true),
        new OA\Property(property: 'categoria_id', type: 'integer', format: 'int64', example: 1, nullable: true),
        new OA\Property(property: 'marca_id', type: 'integer', format: 'int64', example: 1, nullable: true)
    ]
)]
class SwaggerInfo
{
}
