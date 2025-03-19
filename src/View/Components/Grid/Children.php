<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Illuminate\View\View;

class Children extends Component
{
    public function __construct(
        private array | Collection $children = []
    ) {}

    public function render(): View
    {
        return view('sitefrog::components.grid.children', [
            'children' => $this->children
        ]);
    }
}
