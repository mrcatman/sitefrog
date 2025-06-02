<?php

namespace Sitefrog\View\Components\Form\Builder;

use Sitefrog\View\Components\Form\Fields\Input;
use Sitefrog\View\Form\Form;

class FieldBuilder
{

    public static function getName(): string
    {
        return '';
    }

    public static function getForm(): Form
    {
        return new Form(
            fields: [
                new Input(
                    name: 'name',
                    label: 'test',
                    rules: ['required']
                )
            ]
        );
    }

    public static function build()
    {

    }


}
