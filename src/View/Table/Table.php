<?php

namespace Sitefrog\View\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Sitefrog\Traits\MagicGetSet;

class Table
{
    use MagicGetSet;
    private Collection $columns;
    private LengthAwarePaginator $items;

    public const REQUEST_KEYS = [
        'sort_by' => '_sf_table_sort_by',
        'sort_order' => '_sf_table_sort_order',
    ];

    private ?string $sortBy = null;
    private ?string $sortOrder = null;

    public function __construct(
        private Builder $query,
        private ?int $perPage = null,
        private string $defaultSortBy = 'id',
        private string $defaultSortOrder = 'desc',
        array | Collection $columns = [],
    ) {
        if (!$this->perPage) {
            $this->perPage = config('sitefrog.admin.items_per_page');
        }
        $this->columns = collect($columns);
    }

    public function getColumnByName(string $name): ?Column
    {
        return $this->columns->firstWhere(function($column) use ($name) {
            return $column->getName() === $name;
        });
    }

    public function addColumn(Column $column)
    {
        $this->columns->push($column);
    }

    public function fetchItems()
    {
        $request = request();

        $this->sortBy = $request->get(self::REQUEST_KEYS['sort_by'], $this->defaultSortBy);
        $column = $this->getColumnByName($this->sortBy);
        if (!$column || !$column->getSortable()) {
            $this->sortBy = $this->defaultSortBy;
        }

        $this->sortOrder = $request->get(self::REQUEST_KEYS['sort_order'], $this->defaultSortOrder);
        if (!in_array($this->sortOrder, ['asc', 'desc'])) {
            $this->sortOrder = $this->defaultSortOrder;
        }

        $this->items = $this->query
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->perPage);
    }
}
