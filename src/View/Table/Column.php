<?php

namespace Sitefrog\View\Table;
use Sitefrog\Traits\MagicGetSet;

class Column
{
    use MagicGetSet;

    public function __construct(
        private string $name,
        private ?string $label = null,
        private ?bool $sortable = false,
        private $formatter = null
    )
    {
    }


    public function render($item)
    {
        if ($this->formatter) {
            return call_user_func($this->formatter, $item, $this);
        }

        return $item[$this->name];

    }


}
