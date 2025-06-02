<?php

namespace Modules\Auth\Controllers\Admin;

use Sitefrog\View\Components\Form\Fields\FormGroup;
use Sitefrog\View\Components\Form\Fields\Input;
use Sitefrog\View\Controllers\AdminSettingsController;
use Sitefrog\View\Form\Form;

class UsersSettingsController extends AdminSettingsController {

    protected function getMappings()
    {
        return [
          //  ''
        ];
    }

    protected function buildForm()
    {
        return new Form(
            fields: [
                new FormGroup(
                    label: __('sitefrog.auth::settings.common'),
                    fields: [
                        new Input(
                            name: 'username',
                            label: __('sitefrog.auth::settings.registration_enabled'),
                            rules: ['required'] //todo: handle unconfirmed users, handle checkboxes
                        ),
                        new Input(
                            name: 'email',
                            type: 'email',
                            label: __('sitefrog.auth::fields.email'),
                            rules: ['required', 'email']
                        ),
                    ]
                ),
            ]
        );
    }

}
