<?php

namespace Sitefrog\View\Components\Form\Fields;

use Sitefrog\View\Components\Form\Field;

class Checkbox extends Field
{
    public function __construct(
        protected string $name,
        protected $value = null,
        protected ?array $attrs = [],
        protected ?string $label = null,
        protected ?string $description = null,
        protected ?array $rules = []
    )
    {
        parent::__construct(
            name: $name,
            value: $value,
            label: $label,
            description: $description,
            attrs: $attrs,
            rules: $rules
        );
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.form.checkbox';
    }

}
