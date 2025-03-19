<?php
namespace Sitefrog\View\Menu;

use Illuminate\Support\Collection;
use Sitefrog\Traits\MagicGetSet;

class Menu {

    use MagicGetSet;
    private Collection $items;
    public function __construct()
    {
        $this->items = collect([]);
    }

    public function addItems(array | Collection $items)
    {
        foreach ($items as $item) {
            $this->items->push($item);
        }
    }

}
