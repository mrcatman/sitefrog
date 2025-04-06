<?php

namespace Sitefrog\View\Components\Admin;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;

class FiltersWrapper extends Component
{
    public function __construct(
        public array | Collection $children
    ) {}

    public static function getTemplate(): string {
        return 'sitefrog::components.admin.filters-wrapper';
    }

}
