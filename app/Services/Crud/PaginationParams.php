<?php

namespace App\Services\Crud;

final class PaginationParams
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly ?string $search,
        public readonly ?string $sortBy,
        public readonly string $sortDir,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $page = max(1, (int) ($data['page'] ?? 1));
        $perPage = (int) ($data['per_page'] ?? 15);
        $perPage = min(max(1, $perPage), 100);
        $search = isset($data['search']) ? trim((string) $data['search']) : null;
        $search = $search === '' ? null : $search;
        $sortBy = isset($data['sort_by']) ? trim((string) $data['sort_by']) : null;
        $sortBy = $sortBy === '' ? null : $sortBy;
        $sortDir = strtolower((string) ($data['sort_dir'] ?? 'asc'));
        $sortDir = $sortDir === 'desc' ? 'desc' : 'asc';

        return new self($page, $perPage, $search, $sortBy, $sortDir);
    }
}

