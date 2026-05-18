<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

final class ProductoFotoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'url' => Storage::disk('public')->url($this->resource->ruta),
            'ruta' => $this->resource->ruta,
            'nombre_original' => $this->resource->nombre_original,
            'mime_type' => $this->resource->mime_type,
            'tamano_bytes' => $this->resource->tamano_bytes,
            'created_at' => $this->resource->created_at,
        ];
    }
}

