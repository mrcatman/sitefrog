<?php

namespace Sitefrog\View\Components;

use Illuminate\View\View;
use Sitefrog\Facades\AssetManager;
use Sitefrog\View\Component;

class Assets extends Component
{
    public function __construct(
        private string $type = 'css',
    )
    {
    }

    public function render(): View
    {
        $assets = match ($this->type) {
            'css' => AssetManager::getCss(),
            'js' => AssetManager::getJs(),
            default => throw new \Exception("Unknown asset type: $this->type"),
        };
        return view("sitefrog::components.assets.".$this->type, [
            'assets' => $assets
        ]);
    }
}
