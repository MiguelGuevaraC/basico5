<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Services\Productos\ProductoFotoService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(
        private readonly ProductoFotoService $fotoService,
    ) {
    }

    public function index(): \Illuminate\View\View
    {
        return view('productos.index');
    }

    public function list(): JsonResponse
    {
        $perPage = request()->input('per_page', 10);
        $page = request()->input('page', 1);
        
        $paginator = Producto::query()
            ->with(['categoria:id,nombre', 'marca:id,nombre', 'fotos'])
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $paginator->getCollection()
            ->map(static fn (Producto $p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => (string)$p->precio,
                'stock' => $p->stock,
                'categoria_id' => $p->categoria_id,
                'categoria' => $p->categoria?->nombre ?? '',
                'marca_id' => $p->marca_id,
                'marca' => $p->marca?->nombre ?? '',
                'fotos' => $p->fotos->map(fn($f) => ['id' => $f->id, 'ruta' => $f->ruta, 'url' => asset('storage/' . $f->ruta)]),
            ])
            ->values();

        return response()->json([
            'success' => true, 
            'data' => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ]);
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        $producto = Producto::query()->create($request->validated());

        if ($request->hasFile('fotos')) {
            $this->fotoService->store($producto, $request->file('fotos'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto creado.',
            'data' => ['id' => $producto->id],
        ]);
    }

    public function update(UpdateProductoRequest $request, Producto $producto): JsonResponse
    {
        $producto->update($request->validated());

        if ($request->hasFile('fotos')) {
            $this->fotoService->store($producto, $request->file('fotos'));
        }

        $fotosEliminar = $request->input('fotos_eliminar', []);
        if (is_array($fotosEliminar) && count($fotosEliminar) > 0) {
            foreach ($fotosEliminar as $fotoId) {
                $foto = $producto->fotos()->find($fotoId);
                if ($foto) {
                    $this->fotoService->delete($producto, $foto);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado.',
        ]);
    }

    public function destroy(Producto $producto): JsonResponse
    {
        try {
            $producto->delete();
        } catch (QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el producto.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado.',
        ]);
    }
}
