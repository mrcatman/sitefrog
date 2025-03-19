<?php

namespace Sitefrog\View\Components\Form;

use Sitefrog\View\Component;
use Illuminate\View\View;

use Sitefrog\View\Form\Form as FormClass;

class Form extends Component
{
    public function __construct(
        private FormClass $form,
        private bool $isHtmxRequest = false,
    ) {}

    public function buildGrid()
    {

    }

    public function render(): View
    {
        return view('sitefrog::components.form', [
            'form' => $this->form,
            'isHtmxRequest' => $this->isHtmxRequest,
        ]);
    }


}
