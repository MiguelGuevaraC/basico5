<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\MarcaApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\ProductoFotoApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->name('api.auth.login');
});

Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::apiResource('categorias', CategoriaApiController::class)->names('api.v1.categorias');
    Route::apiResource('marcas', MarcaApiController::class)->names('api.v1.marcas');
    Route::apiResource('productos', ProductoApiController::class)->names('api.v1.productos');

    Route::post('/productos/{producto}/fotos', [ProductoFotoApiController::class, 'store'])->name('api.v1.productos.fotos.store');
    Route::delete('/productos/{producto}/fotos/{foto}', [ProductoFotoApiController::class, 'destroy'])->name('api.v1.productos.fotos.destroy');
});

