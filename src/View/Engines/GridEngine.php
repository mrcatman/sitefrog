<?php
namespace Sitefrog\View\Engines;

use Illuminate\Contracts\View\Engine;
use Sitefrog\View\Components\Grid\Grid;

class GridEngine implements Engine
{
    public function get($file, array $params = [])
    {
        return (new Grid(
            file: $file,
            params: $params
        ))->render();
    }
}
