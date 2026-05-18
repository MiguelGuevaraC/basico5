<?php

namespace App\Services\Catalogo;

use App\Models\Producto;
use App\Services\Crud\AbstractCrudService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ProductoCrudService extends AbstractCrudService
{
    protected function baseQuery(): Builder
    {
        return Producto::query()->with(['categoria', 'marca', 'fotos']);
    }

    protected function searchableColumns(): array
    {
        return ['nombre'];
    }

    protected function allowedSortColumns(): array
    {
        return ['id', 'nombre', 'precio', 'stock', 'created_at'];
    }

    public function create(array $data): Model
    {
        return parent::create($data)->load(['categoria', 'marca', 'fotos']);
    }

    public function update(Model $model, array $data): Model
    {
        return parent::update($model, $data)->load(['categoria', 'marca', 'fotos']);
    }
}
