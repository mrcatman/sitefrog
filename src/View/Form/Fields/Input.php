<?php

namespace Sitefrog\View\Form\Fields;

use Sitefrog\View\Form\Field;

class Input extends Field
{
    protected string $view = 'sitefrog::components.form.input';

    public function __construct(
        protected string $name,
        protected string $type = 'text',
        protected ?string $value = null,
        protected ?string $placeholder = null,
        protected ?string $label = null,
        protected ?array $validationRules = []
    )
    {
        parent::__construct(
            name: $name,
            value: $value,
            label: $label,
            validationRules: $this->validationRules
        );
    }

}
