<?php

namespace Sitefrog\View\Components\Grid;

use Sitefrog\View\Component;
use Illuminate\View\View;

class Content extends Component
{

    public function render(): View
    {
        return view('sitefrog::components.grid.content');
    }
}
