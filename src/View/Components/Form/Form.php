<?php

namespace Sitefrog\View\Components\Form;

use Sitefrog\View\Component;
use Illuminate\View\View;

use Sitefrog\View\Form\Form as FormClass;

class Form extends Component
{
    public function __construct(
        public FormClass $form,
    ) {
    }

    public static function getTemplate(): string {
        return 'sitefrog::components.form';
    }


}
