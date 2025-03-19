<?php

namespace Sitefrog\View\Components\Form;

use Illuminate\View\View;
use Sitefrog\View\Form\Field;

class FieldWrapper
{
    public function __construct(private Field $field) {}

    public function render(): View
    {
        return view('sitefrog::components.form.field-wrapper', [
            'field' => $this->field
        ]);
    }


}
