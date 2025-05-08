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
        string $id,
        bool $inline = false,
        array $params = []
    )
    {
        return AssetManager::addCss(
            source: Storage::disk('themes')->path("$this->name/$source"),
            id: $id,
            inline: $inline,
            params: $params
        );
    }

    public function addJs(
        string $source,
        string $id,
        bool $inline = false,
        array $params = []
    )
    {
        return AssetManager::addJs(
            source: Storage::disk('themes')->path("$this->name/$source"),
            id: $id,
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
        View::prependNamespace('sitefrog', Storage::disk('themes')->path("$this->name/resources/views"));
    }

}
