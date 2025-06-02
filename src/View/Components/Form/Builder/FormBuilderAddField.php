<?php

namespace Sitefrog\View\Components\Form\Builder;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Sitefrog\View\Form\Builder\FormBuilder;

class FormBuilderAddField extends Component
{
    private Collection $fieldBuilders;

    public function __construct() {
        $formBuilder = app()->make(FormBuilder::class);

        $this->fieldBuilders = $formBuilder->getFieldBuilders();
    }

    public static function getTemplate(): string {
        return 'sitefrog::components.form.builder';
    }


}
