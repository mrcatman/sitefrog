<?php

namespace Themes\Default;

use Sitefrog\View\Themes\BaseTheme;

class Theme extends BaseTheme
{
    protected string $name = 'default';

    public function load()
    {
        parent::load();

        $this->addCss('resources/css/index.scss', 'theme_css');
        $this->setDefaultLayout('resources/views/layouts/default.json');
    }

}
