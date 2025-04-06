<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;

class Children extends Component
{
    public function __construct(
        public array | Collection $children = [],
    ) {
        $this->children = collect($children);

    }

    public static function getTemplate(): string {
        return 'sitefrog::components.grid.children';
    }

}
