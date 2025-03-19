<?php
namespace Sitefrog\View\Menu;

use Sitefrog\Traits\MagicGetSet;

class MenuIcon {

    use MagicGetSet;
    public function __construct(
        private ?string $picture,
        private ?string $icon,
    ) {}

}
