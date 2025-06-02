<?php

namespace Sitefrog\View\Components\Form\Builder;

use Sitefrog\View\Component;
use Sitefrog\View\Form\Builder\CustomFormsRepository;

class FormBuilderComponent extends Component
{
    private CustomFormsRepository $customFormsRepository;

    public function __construct(
        string $name,
    ) {
        $this->customFormsRepository = app()->make(CustomFormsRepository::class);

        $form = $this->customFormsRepository->getBy('name', $name);
    }

    public static function getTemplate(): string {
        return 'sitefrog::components.form.builder.index';
    }


}
