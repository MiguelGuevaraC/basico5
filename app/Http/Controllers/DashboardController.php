<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('dashboard.index');
    }

    public function stats(): JsonResponse
    {
        $productosPorCategoria = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', Producto::query()->raw('count(*) as total'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $productosPorMarca = Producto::query()
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', Producto::query()->raw('count(*) as total'))
            ->groupBy('marcas.id', 'marcas.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stockPorCategoria = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', Producto::query()->raw('sum(stock) as total'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categorias' => Categoria::query()->count(),
                'marcas' => Marca::query()->count(),
                'productos' => Producto::query()->count(),
                'productosPorCategoria' => $productosPorCategoria,
                'productosPorMarca' => $productosPorMarca,
                'stockPorCategoria' => $stockPorCategoria,
            ],
        ]);
    }
}
