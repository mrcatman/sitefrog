<?php

namespace Sitefrog\View\Components;

use Sitefrog\View\Component;

class Dropdown extends Component
{
    public function __construct(
        public string $title = '',
        public array $items = []
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.dropdown';
    }
}
