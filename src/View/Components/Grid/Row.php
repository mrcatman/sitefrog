<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Illuminate\View\View;

class Row extends Component
{
    public function __construct(
        protected array | Collection $children = [],
        protected ?string $variant = null,
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.grid.row';
    }

    public function getChildren(): array | Collection | null
    {
        return $this->children;
    }

}
