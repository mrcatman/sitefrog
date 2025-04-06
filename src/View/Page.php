<?php

namespace Sitefrog\View;

use Sitefrog\Traits\MagicGetSet;

class Page {

    use MagicGetSet;
    private string $title = '';

    public function getFullTitle()
    {
        return $this->title && $this->title != '' ? $this->title . ' | Sitefrog' : $this->title; // todo
    }
}
