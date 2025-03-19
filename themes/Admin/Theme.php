<?php

namespace Themes\Admin;

use Sitefrog\View\Themes\BaseTheme;

class Theme extends BaseTheme
{
    protected string $name = 'admin';

    public function load()
    {
        parent::load();

        $this->addCss('resources/css/index.scss');
        $this->setDefaultLayout('resources/views/layouts/default.json');
    }

}
