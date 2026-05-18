<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReporteController extends Controller
{
    public function marcas(): Response
    {
        $marcas = Marca::query()->orderBy('nombre')->get();

        $pdf = Pdf::loadView('reportes.marcas', compact('marcas'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_marcas.pdf');
    }

    public function productos(string $orden = 'completo'): Response
    {
        $query = Producto::query()->with(['categoria', 'marca', 'fotos' => function ($q) {
            $q->latest()->limit(1);
        }]);

        $tituloOrden = 'Listado Completo';
        $agrupacion = null;
        $secciones = [];

        switch ($orden) {
            case 'marca':
                $query->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                    ->orderBy('marcas.nombre')
                    ->select('productos.*');
                $tituloOrden = 'Ordenado por Marca';
                $agrupacion = 'marca';
                break;
            case 'categoria':
                $query->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                    ->orderBy('categorias.nombre')
                    ->select('productos.*');
                $tituloOrden = 'Ordenado por Categoría';
                $agrupacion = 'categoria';
                break;
            default:
                $query->orderBy('id');
        }

        $productos = $query->get();

        if ($agrupacion) {
            $secciones = $productos->groupBy(function ($producto) use ($agrupacion) {
                $relacion = $producto->$agrupacion;
                return $relacion ? $relacion->nombre : 'Sin ' . $agrupacion;
            });
        }

        $pdf = Pdf::loadView('reportes.productos', compact('productos', 'tituloOrden', 'agrupacion', 'secciones'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_productos.pdf');
    }
}
