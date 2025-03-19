<?php

namespace Sitefrog\View\Themes;
use Illuminate\Container\Container;

class ThemeManager
{
    private string $name = 'default';
    private BaseTheme $theme;

    public function __construct(
        private Container $app
    ) {
    }


    public function set($name)
    {
        $this->name = $name;
        $this->load();

    }

    public function load()
    {
        $className = "Themes\\$this->name\\Theme";
        $theme = $this->app->make($className);
        $theme->load();

        $this->theme = $theme;
    }

}
