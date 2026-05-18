<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

    Route::prefix('api')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'stats'])->name('api.dashboard');

        Route::get('/categorias', [CategoriaController::class, 'list'])->name('api.categorias.list');
        Route::get('/categorias/options', [CategoriaController::class, 'options'])->name('api.categorias.options');
        Route::post('/categorias/crear', [CategoriaController::class, 'store'])->name('api.categorias.store');
        Route::post('/categorias/{categoria}/editar', [CategoriaController::class, 'update'])->name('api.categorias.update');
        Route::post('/categorias/{categoria}/eliminar', [CategoriaController::class, 'destroy'])->name('api.categorias.destroy');

        Route::get('/marcas', [MarcaController::class, 'list'])->name('api.marcas.list');
        Route::get('/marcas/options', [MarcaController::class, 'options'])->name('api.marcas.options');
        Route::post('/marcas/crear', [MarcaController::class, 'store'])->name('api.marcas.store');
        Route::post('/marcas/{marca}/editar', [MarcaController::class, 'update'])->name('api.marcas.update');
        Route::post('/marcas/{marca}/eliminar', [MarcaController::class, 'destroy'])->name('api.marcas.destroy');

        Route::get('/productos', [ProductoController::class, 'list'])->name('api.productos.list');
        Route::post('/productos/crear', [ProductoController::class, 'store'])->name('api.productos.store');
        Route::post('/productos/{producto}/editar', [ProductoController::class, 'update'])->name('api.productos.update');
        Route::post('/productos/{producto}/eliminar', [ProductoController::class, 'destroy'])->name('api.productos.destroy');
    });

    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/marcas', [ReporteController::class, 'marcas'])->name('marcas');
        Route::get('/productos/{orden?}', [ReporteController::class, 'productos'])->name('productos');
    });
});

Route::get('/api/docs/json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'));
})->name('api.docs.json');

Route::get('/api/redoc', function () {
    return view('redoc');
})->name('api.redoc');
