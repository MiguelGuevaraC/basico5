<?php

namespace App\Services\Catalogo;

use App\Models\Categoria;
use App\Services\Crud\AbstractCrudService;
use Illuminate\Database\Eloquent\Builder;

final class CategoriaCrudService extends AbstractCrudService
{
    protected function baseQuery(): Builder
    {
        return Categoria::query();
    }

    protected function searchableColumns(): array
    {
        return ['nombre'];
    }

    protected function allowedSortColumns(): array
    {
        return ['id', 'nombre', 'created_at'];
    }
}

