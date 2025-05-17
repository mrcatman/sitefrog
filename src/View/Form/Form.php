<?php

namespace Sitefrog\View\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Sitefrog\Traits\MagicGetSet;

class Form
{
    use MagicGetSet;

    private string $method = 'POST';
    private string | null $action = null;

    private bool $submitted = false;

    private array $data;

    private Collection $fields;

    private ?string $submitLabel = null;

    public function __construct(
        private ?string $name = null,
        array | Collection $fields = [],
    ) {
        $this->fields = collect($fields);
    }

    public function onSubmit($fn)
    {
        if (request()->form() !== $this->name) {
            return;
        }

        $data = request()->all();
        foreach ($data as $name => $val) {
            $this->getFieldByName($name)?->setValue($val);
        }

        $validator = Validator::make(
            $data,
            $this->getValidationRules()
        );

        if ($validator->fails()) {
            $this->setErrors($validator->errors()->getMessages());
        } else {
            $this->data = $validator->validated();
            $fn($this);
        }
    }

    private function getFieldByName(string $name) // todo: handle FormGroups
    {
        return $this->fields->firstWhere(function($field) use ($name) {
            return $field->getName() === $name;
        });
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

    public function getValidationRules()
    {
        $rules = [];
        foreach ($this->getFields() as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function isSubmitted()
    {
        return !empty($this->data);
    }

}
