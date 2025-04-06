<?php

namespace Sitefrog\View\Components;

use Sitefrog\View\Component;

class Button extends Component
{
    public function __construct(
        public string $content = '',
        public string $variant = 'primary',
        public ?string $href = null,
        public array $attrs = [],
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.button';
    }
}
