<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Models\Marca;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class MarcaController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('marcas.index');
    }

    public function list(): JsonResponse
    {
        $rows = Marca::query()
            ->select(['id', 'nombre'])
            ->orderByDesc('id')
            ->get();

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function options(): JsonResponse
    {
        $search = request()->input('search', '');
        $query = Marca::query()->select(['id', 'nombre']);
        
        if ($search !== '') {
            $query->where('nombre', 'like', "%{$search}%");
        }
        
        $rows = $query->orderBy('nombre')->limit(20)->get()
            ->map(static fn (Marca $m) => ['id' => $m->id, 'text' => $m->nombre])
            ->values();

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function store(StoreMarcaRequest $request): JsonResponse
    {
        $marca = Marca::query()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Marca creada.',
            'data' => ['id' => $marca->id],
        ]);
    }

    public function update(UpdateMarcaRequest $request, Marca $marca): JsonResponse
    {
        $marca->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada.',
        ]);
    }

    public function destroy(Marca $marca): JsonResponse
    {
        try {
            $marca->delete();
        } catch (QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la marca.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada.',
        ]);
    }
}
