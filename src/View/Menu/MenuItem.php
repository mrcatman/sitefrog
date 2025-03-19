<?php
namespace Sitefrog\View\Menu;

use Illuminate\Support\Collection;
use Sitefrog\Traits\MagicGetSet;

class MenuItem {

    use MagicGetSet;
    public function __construct(
        private string $title,
        private string $url = '#',
        private ?int $position = 0,
        private ?MenuIcon $icon = null,
        private array | Collection $children = [],
    ) {
        $this->children = collect($this->children);
    }

    public function hasChildren(): bool {
        return $this->children->isNotEmpty();
    }

}
