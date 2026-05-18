<?php

namespace App\Services\Productos;

use App\Models\Producto;
use App\Models\ProductoFoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

final class ProductoFotoService
{
    public function store(Producto $producto, array $archivos): Collection
    {
        $files = collect($archivos)->filter(fn (mixed $file) => $file instanceof UploadedFile)->values();
        if ($files->isEmpty()) {
            throw ValidationException::withMessages([
                'fotos' => ['Debe enviar una o más fotos.'],
            ]);
        }

        return DB::transaction(function () use ($producto, $files): Collection {
            return $files->map(function (UploadedFile $file) use ($producto): ProductoFoto {
                $ruta = $file->storePublicly('productos/'.$producto->getKey(), ['disk' => 'public']);

                return ProductoFoto::query()->create([
                    'producto_id' => $producto->getKey(),
                    'ruta' => $ruta,
                    'nombre_original' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType() ?? 'application/octet-stream',
                    'tamano_bytes' => (int) $file->getSize(),
                ]);
            });
        });
    }

    public function delete(Producto $producto, ProductoFoto $foto): void
    {
        if ((int) $foto->producto_id !== (int) $producto->getKey()) {
            throw ValidationException::withMessages([
                'foto' => ['La foto no pertenece al producto.'],
            ]);
        }

        DB::transaction(function () use ($foto): void {
            Storage::disk('public')->delete($foto->ruta);
            $foto->delete();
        });
    }
}

