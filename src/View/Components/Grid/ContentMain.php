<?php

namespace Sitefrog\View\Components\Grid;

use Sitefrog\View\Component;
use Illuminate\View\View;
use Sitefrog\Facades\PageData;

class ContentMain extends Component
{

    public function render(): View
    {
        return view(PageData::getView(), PageData::getParams());
    }
}
