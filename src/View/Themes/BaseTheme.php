<?php

namespace Sitefrog\View\Themes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Sitefrog\Facades\AssetManager;
use Sitefrog\Facades\LayoutManager;

class BaseTheme
{
    protected string $name;

    public function addCss(
        string $source,
        bool $inline = false,
        array $params = []
    )
    {
        return AssetManager::addCss(
            source: Storage::disk('themes')->path("$this->name/$source"),
            inline: $inline,
            params: $params
        );
    }

    public function addJs(
        string $source,
        bool $inline = false,
        array $params = []
    )
    {
        return AssetManager::addJs(
            source: Storage::disk('themes')->path("$this->name/$source"),
            inline: $inline,
            params: $params
        );
    }

    public function setDefaultLayout($layout)
    {
        LayoutManager::setDefault(Storage::disk('themes')->path("$this->name/$layout"));
    }

    public function load()
    {
        View::prependNamespace('sitefrog', Storage::disk('themes')->path("$this->name/views"));
    }

}
