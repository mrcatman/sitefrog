<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Illuminate\View\View;

class Container extends Component
{
    public function __construct(
        private array | Collection $children = []
    ) {}

    public function render(): View
    {
        return view('sitefrog::components.grid.container', [
            'children' => !empty($this->children) ? $this->children : []
        ]);
    }
}
