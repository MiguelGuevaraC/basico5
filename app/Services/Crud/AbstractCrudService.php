<?php

namespace App\Services\Crud;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

abstract class AbstractCrudService
{
    abstract protected function baseQuery(): Builder;

    abstract protected function searchableColumns(): array;

    protected function allowedSortColumns(): array
    {
        return ['id'];
    }

    public function paginate(PaginationParams $params): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        if ($params->search !== null) {
            $columns = $this->searchableColumns();
            $query->where(function (Builder $builder) use ($columns, $params): void {
                foreach ($columns as $column) {
                    $builder->orWhere($column, 'ilike', '%'.$params->search.'%');
                }
            });
        }

        $sortBy = $params->sortBy;
        if ($sortBy !== null) {
            $this->assertSortColumnAllowed($sortBy);
            $query->orderBy($sortBy, $params->sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate(
            perPage: $params->perPage,
            page: $params->page,
        );
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $model = $this->newModel();
            $model->fill($data);
            $model->save();

            return $model->refresh();
        });
    }

    public function update(Model $model, array $data): Model
    {
        return DB::transaction(function () use ($model, $data): Model {
            $model->fill($data);
            $model->save();

            return $model->refresh();
        });
    }

    public function delete(Model $model): void
    {
        DB::transaction(static function () use ($model): void {
            $model->delete();
        });
    }

    protected function newModel(): Model
    {
        $modelClass = $this->baseQuery()->getModel()::class;

        return new $modelClass();
    }

    private function assertSortColumnAllowed(string $column): void
    {
        if (! in_array($column, $this->allowedSortColumns(), true)) {
            throw new InvalidArgumentException('Campo de ordenamiento inválido.');
        }
    }
}

