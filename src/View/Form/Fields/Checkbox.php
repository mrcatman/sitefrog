<?php

namespace Sitefrog\View\Form\Fields;

use Illuminate\Support\Collection;
use Sitefrog\View\Form\Field;

class Checkbox extends Field
{
    public function __construct(
        protected string $name,
        protected $value = null,
        protected ?array $attrs = [],
        protected ?string $label = null,
        protected ?array $rules = []
    )
    {
        parent::__construct(
            name: $name,
            value: $value,
            label: $label,
            attrs: $attrs,
            rules: $rules
        );
    }
    public static function getTemplate(): string
    {
        return 'sitefrog::components.form.checkbox';
    }

}
