<?php

namespace Sitefrog\View\Components\Form\Builder;

use Illuminate\Support\Collection;
use Sitefrog\Facades\FormManager;
use Sitefrog\View\Component;
use Sitefrog\View\Form\Builder\FormBuilder;

class FormBuilderAddField extends Component
{
    public Collection $fieldBuilders;

    public function __construct() {
        /** @var $formBuilder FormBuilder */
        $formBuilder = app()->make(FormBuilder::class);
        $this->fieldBuilders = $formBuilder->getFieldBuilders()->flip();

        FormManager::onSubmit('form_builder_add_field', function() {
            return 'test callback';
        });
    }

    public static function getTemplate(): string {
        return 'sitefrog::components.form.builder.add-field';
    }


}
