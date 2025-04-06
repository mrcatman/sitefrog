<?php

namespace Sitefrog\View\Form;

use Sitefrog\View\Component;
use Sitefrog\Traits\MagicGetSet;

class Field extends Component
{
    use MagicGetSet;
    protected string $view;

    private array $errors = [];

    public function __construct(
        protected string $name,
        protected ?string $value = null,
        protected ?string $label = null,
        protected ?array $attrs = [],
        protected ?array $validationRules = []
    )
    {
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }


}
