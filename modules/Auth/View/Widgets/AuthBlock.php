<?php

namespace Modules\Auth\View\Widgets;

use Illuminate\View\View;
use Modules\Auth\Helpers\AuthHelper;
use Sitefrog\View\Components\WidgetWrapper;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Widget;

class AuthBlock extends Widget
{
    private Form $form;

    public function load()
    {
        $this->form = AuthHelper::getLoginForm('login-widget');
    }

    public function getConfig()
    {
        return new Form(
            fields: [
                new Input(
                    name: 'test',
                    label: 'test field',
                    validationRules: ['required']
                ),
            ]
        );
    }

    public function render(): View | string
    {
        return (new WidgetWrapper(
            title: 'Login widget',
            content: view('sitefrog.auth::components.auth-block', [
                'form' => $this->form
            ])
        ))->tryRender();
    }
}
