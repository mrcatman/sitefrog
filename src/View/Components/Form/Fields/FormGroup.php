<?php

namespace Sitefrog\View\Components\Form\Fields;

use Illuminate\Support\Collection;
use Sitefrog\View\Components\Form\Field;

class FormGroup extends Field
{

    protected Collection $fields;

    public function __construct(
        protected ?string $label = null,
        ?array $fields = [],
        protected ?array $attrs = [],
    )
    {
        $this->fields = collect($fields);
        parent::__construct(
            name: '',
            label: $label,
            attrs: $attrs,
        );
    }

    public function getValidationRules()
    {
        $rules = [];
        foreach ($this->getFields() as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function setValues(mixed $values)
    {
        $this->fields->each(function($field) use ($values) {
            $field->setValues($values);
        });
    }

    public function setErrors(mixed $errors)
    {
        $this->fields->each(function($field) use ($errors) {
            $field->setErrors($errors);
        });
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.form.group';
    }

}
