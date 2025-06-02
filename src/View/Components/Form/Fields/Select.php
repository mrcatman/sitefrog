<?php

namespace Sitefrog\View\Components\Form\Fields;

use Illuminate\Support\Collection;
use Sitefrog\View\Components\Form\Field;

class Select extends Field
{
    public function __construct(
        protected string $name,
        protected array | Collection $options = [],
        protected ?bool $multiple = false,
        protected $value = null,
        protected ?array $attrs = [],
        protected ?string $label = null,
        protected ?string $description = null,
        protected ?array $rules = []
    )
    {
        $this->options = collect($this->options);

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
        return 'sitefrog::components.form.select';
    }

}
