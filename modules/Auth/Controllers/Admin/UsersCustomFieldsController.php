<?php

namespace Modules\Auth\Controllers\Admin;
use Sitefrog\Facades\Page;
use Sitefrog\View\Components\Box;
use Sitefrog\View\Components\Form\Builder\FormBuilderComponent;
use Sitefrog\View\Controllers\BaseController;

class UsersCustomFieldsController extends BaseController {

    public function index()
    {

        return $this->renderGrid([
            new Box(
                heading: Page::getTitle(),
                children: [
                    'main' => [
                        new FormBuilderComponent(
                            name: 'user-info'
                        )
                    ]
                ]
            )
        ]);

    }

}
