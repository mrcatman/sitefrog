<?php

namespace Sitefrog\View\Components\Form;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;

use Sitefrog\View\Form\Form as FormClass;

class FormComponent extends Component
{
    public function __construct(
        public ?FormClass $form = null,
        ?string $name = null,
        array | Collection | null $fields = [],
    ) {
        if (!$this->form) {
            $this->form = new FormClass($name, $fields);
        }
    }

    public static function getTemplate(): string {
        return 'sitefrog::components.form';
    }


}
