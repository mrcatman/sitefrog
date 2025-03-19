<?php

namespace Sitefrog\View\Components\Form;

use Sitefrog\View\Component;
use Illuminate\View\View;

class Submit extends Component
{

    public function __construct(

    ) {
    }


    public function render(): View
    {
        return view('sitefrog::components.form.submit', [

        ]);
    }


}
