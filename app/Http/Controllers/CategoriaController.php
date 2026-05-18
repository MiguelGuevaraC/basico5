<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('categorias.index');
    }

    public function list(): JsonResponse
    {
        $rows = Categoria::query()->select(['id', 'nombre'])->orderByDesc('id')->get();
        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function options(): JsonResponse
    {
        $search = request()->input('search', '');
        $query = Categoria::query()->select(['id', 'nombre']);
        
        if ($search !== '') {
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        $rows = $query->orderBy('nombre')->limit(20)->get()
            ->map(static fn (Categoria $c) => ['id' => $c->id, 'text' => $c->nombre])
            ->values();

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        $categoria = Categoria::query()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada.',
            'data' => ['id' => $categoria->id],
        ]);
    }

    public function update(UpdateCategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        $categoria->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada.',
        ]);
    }

    public function destroy(Categoria $categoria): JsonResponse
    {
        try {
            $categoria->delete();
        } catch (QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la categoría.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada.',
        ]);
    }
}
