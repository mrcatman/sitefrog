<?php

namespace Sitefrog\View\Components;

use Sitefrog\View\Component;

class ModalsContainer extends Component
{
    public function __construct(
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.modals-container';
    }
}
