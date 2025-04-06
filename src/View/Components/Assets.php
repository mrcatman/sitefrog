<?php

namespace Sitefrog\View\Components;

use Illuminate\Support\Collection;
use Sitefrog\Facades\AssetManager;
use Sitefrog\View\Component;

class Assets extends Component
{
    public Collection $assets;

    public function __construct(
        public string $type = 'css',
    ) {
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.assets';
    }

    public function beforeRender(): void
    {
        $this->assets = match ($this->type) {
            'css' => AssetManager::getCss(),
            'js' => AssetManager::getJs(),
            'hyperscript' => AssetManager::getHyperscript(),
            default => throw new \Exception("Unknown asset type: $this->type"),
        };
    }
}
