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
        if (request()->input('_sf_form') !== $this->name) {
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
            foreach ($validator->errors()->getMessages() as $field => $errors) {
                $this->setErrors($field, $errors);
            }
        } else {
            $this->data = $validator->getData();
            $fn($this);
        }
    }

    private function getFieldByName(string $name)
    {
        return $this->fields->firstWhere(function($field) use ($name) {
            return $field->getName() === $name;
        });
    }

    public function setErrors(string $name, array $errors)
    {
        return $this->getFieldByName($name)?->setErrors($errors);
    }

    public function setValues(mixed $values)
    {
        $this->fields->each(function($field) use ($values) {
            $field->setValue($values->{$field->getName()});
        });
    }

    public function getValidationRules()
    {
        $rules = [];
        foreach ($this->getFields() as $field) {
            $rules[$field->getName()] = $field->getValidationRules();
        }
        return $rules;
    }

    public function isSubmitted()
    {
        return !empty($this->data);
    }

}
