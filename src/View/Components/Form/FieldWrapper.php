<?php

namespace Sitefrog\View\Components\Form;

use Sitefrog\View\Component;

class FieldWrapper extends Component
{
    public function __construct(
        public Field $field,
        public ?string $type = null
    ) {
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.form.field-wrapper';
    }


}
