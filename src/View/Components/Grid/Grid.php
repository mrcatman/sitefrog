<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Sitefrog\Facades\ComponentManager;

class Grid extends Component
{

    public function __construct(
        private ?string $file = null,
        public array | Collection | null $layout = null,
    )
    {
        if ($layout) {
            $this->layout = collect($layout);
        }
    }

    private function configToComponent($config)
    {
        $children = collect(!empty($config->children) ? $config->children : [])->map(function ($childConfig) {
            return $this->configToComponent($childConfig);
        });

        $params = !empty($config->params) ? (array)$config->params : [];
        $params['children'] = $children;

        return ComponentManager::makeInstance($config->component, $params);
    }

    public function loadFromFile()
    {
        if (!file_exists($this->file)) {
            throw new \Exception('Grid file not found: '.$this->file);
        }
        $grid = json_decode(file_get_contents($this->file)); // todo: check version, validate, etc
        $layout = collect($grid->layout)->map(function($config) {
            return $this->configToComponent($config);
        });

        $this->layout = $layout;
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.grid';
    }

    public function beforeRender(): void
    {
        if (!empty($this->file)) {
            $this->loadFromFile();
        } else if (empty($this->layout)) {
            throw new \Exception('Specify either layout or file for grid');
        }
    }
}
