<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'nombre' => $this->resource->nombre,
            'precio' => $this->resource->precio,
            'stock' => $this->resource->stock,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'marca' => new MarcaResource($this->whenLoaded('marca')),
            'fotos' => ProductoFotoResource::collection($this->whenLoaded('fotos')),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}

