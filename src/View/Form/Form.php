<?php

namespace Sitefrog\View\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Sitefrog\Traits\MagicGetSet;
use Sitefrog\View\Components\Form\Field;

class Form
{
    use MagicGetSet;

    private string $method = 'POST';
    private string | null $action = null;

    private bool $submitted = false;

    private array $data;

    private Collection $fields;

    private ?string $submitLabel = null;

    private $submitHandler = null;

    private $config = [
        'show_submit' => true
    ];

    public function __construct(
        private ?string $name = null,
        array | Collection $fields = [],
        array | Collection $config = [],
    ) {
        $this->fields = collect($fields);
        $this->setConfig($config);
    }

    public function setConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }


    public function addField(Field $field)
    {
        $this->fields->add($field);
    }

    public function onSubmit(callable $fn)
    {
        $this->submitHandler = $fn;
    }

    public function hasSubmitHandler()
    {
        return !!$this->submitHandler;
    }

    public function submit()
    {
        $request = request();

        $data = $request->all();
        $this->setValues($data);

        $validator = Validator::make(
            $data,
            $this->getValidationRules()
        );

        if ($validator->fails()) {
            $this->setErrors($validator->errors()->getMessages());
        } else {
            $this->data = $validator->validated();
            ($this->submitHandler)($this);
        }
    }

    private function getFieldByName(string $name) // todo: handle FormGroups
    {
        return $this->fields->firstWhere(function($field) use ($name) {
            return $field->getName() === $name;
        });
    }

    public function getFlatFieldsList($fields = null, $list = null): Collection
    {
        if (!$fields) {
            $fields = $this->fields;
        }

        if (!$list) {
            $list = collect([]);
        }

        foreach ($fields as $field) {
            if ($children = $field->getChildren()) {
                $list = $list->merge($this->getFlatFieldsList($children, $list));
            } elseif ($field instanceof Field) {
                $list->push($field);
            }
        }
        return $list;
    }

    public function setValues(mixed $values)
    {
        $this->getFlatFieldsList()->each(function($field) use ($values) {
            $field->setValues($values);
        });
    }

    public function setErrors(mixed $errors)
    {
        $this->getFlatFieldsList()->each(function($field) use ($errors) {
            $field->setErrors($errors);
        });
    }

    public function getValidationRules()
    {
        $rules = [];
        foreach ($this->getFlatFieldsList() as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        };
        return $rules;
    }

    public function isSubmitted()
    {
        return !empty($this->data);
    }

}
