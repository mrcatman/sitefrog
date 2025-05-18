<?php

namespace Sitefrog\View\Form;

use Sitefrog\View\Component;
use Sitefrog\Traits\MagicGetSet;

class Field extends Component
{
    use MagicGetSet;

    private array $errors = [];

    public function __construct(
        protected string $name,
        protected $value = null,
        protected ?string $label = null,
        protected ?array $attrs = [],
        protected ?array $rules = []
    )
    {
    }

    public function getId()
    {
        return 'field_'.$this->name; // todo: add form name?
    }

    public function getValidationRules()
    {
        return [
            $this->name => $this->rules
        ];
    }

    public function setValues(mixed $values)
    {
        if (isset($values->{$this->getName()})) {
            $this->value = $values->{$this->getName()};
        }
    }

    public function setErrors(mixed $errors)
    {
        if (isset($errors[$this->getName()])) {
            $this->errors = $errors[$this->getName()];
        }
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }


}
