<?php

namespace App\Services\Catalogo;

use App\Models\Marca;
use App\Services\Crud\AbstractCrudService;
use Illuminate\Database\Eloquent\Builder;

final class MarcaCrudService extends AbstractCrudService
{
    protected function baseQuery(): Builder
    {
        return Marca::query();
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

