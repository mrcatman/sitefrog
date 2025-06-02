<?php

namespace Sitefrog\View\Components\Form;

use Sitefrog\Traits\MagicGetSet;
use Sitefrog\View\Component;

class Field extends Component
{
    use MagicGetSet;

    private array $errors = [];

    public function __construct(
        protected string $name,
        protected $value = null,
        protected ?string $label = null,
        protected ?string $description = null,
        protected ?array $attrs = [],
        protected ?array $rules = []
    )
    {
    }

    public function getId()
    {
        return 'field_'.$this->name; // todo: add form name?
    }

    public function getName()
    {
        $name = $this->name;
        if (strpos($name, '.') !== false) {
            $name_parts = explode('.', $name);

            $new_name = '';
            foreach ($name_parts as $index => $part) {
                $new_name .= $index > 0 ? '['.$part.']' : $part;
            }
            return $new_name;
        }
        return $name;
    }

    public function getValidationRules()
    {
        $rules = $this->rules;
        if (count($rules) === 0) {
            $rules = ['sometimes'];
        }
        return [
            $this->name => $rules
        ];
    }

    public function setValues(mixed $values)
    {
        $values = is_array($values) ? $values : $values->toArray();
       // if (isset($values[$this->getName()])) {
            $this->value = $values[$this->getName()] ?? '';
       // }
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
