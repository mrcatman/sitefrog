<?php

namespace Sitefrog\View\Components;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;

class Box extends Component
{
    public function __construct(
        public ?string $heading = null,
        public array | Collection $children,
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.box';
    }
}
