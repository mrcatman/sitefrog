<?php

namespace Sitefrog\View\Components\Grid;

use Sitefrog\View\Component;
use Sitefrog\Facades\WidgetManager;
use Illuminate\View\View;

class Block extends Component
{
    public function __construct(
        private string $id
    ) {}

    public function render(): View | string
    {
        $grid = WidgetManager::getAreaGrid($this->id);
        if (!$grid) {
            return '';
        }
        return $grid->tryRender();
    }
}
