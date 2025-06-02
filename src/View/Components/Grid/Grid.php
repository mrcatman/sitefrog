<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Sitefrog\Facades\ComponentManager;

class Grid extends Component
{
    const SUBSTITUTE_PARAM_PREFIX = '$$';

    public function __construct(
        private ?string $file = null,
        public array | Collection | null $layout = null,
        public array | Collection | null $params = null,
    )
    {
        if ($layout) {
            $this->layout = collect($layout);
        }
    }

    private function findViewFile()
    {
        if (!file_exists($this->file)) {
            $finder = app('view')->getFinder();
            return $finder->find($this->file);
        }
        return $this->file;
    }

    private function substituteParams(&$params)
    {
        foreach ($params as $key => $value) {

            if (str_starts_with($value, self::SUBSTITUTE_PARAM_PREFIX)) {
                $param = ltrim($value, self::SUBSTITUTE_PARAM_PREFIX);
                if (!isset($this->params[$param])) { // todo: handle multidimensional
                    throw new \Exception("Param $param not found for $this->file");
                }
                $params[$key] = $this->params[$param];
            }

            if (is_array($value)) {
                $this->substituteParams($value);
            }
        }
    }

    private function configToComponent($config)
    {
        $params = !empty($config->params) ? (array)$config->params : [];

        $childrenPaths = ['children', 'fields']; // todo: rewrite
        foreach ($childrenPaths as $path) {
            if (isset($config->$path)) {
                $children = collect(!empty($config->$path) ? $config->$path : [])->map(function ($childConfig) {
                    return $this->configToComponent($childConfig);
                });
                $params[$path] = $children;
            }
        }

        $this->substituteParams($params);

        return ComponentManager::makeInstance($config->component, $params);
    }

    public function loadFromFile()
    {
        $file = $this->findViewFile();
        $grid = json_decode(file_get_contents($file)); // todo: check version, validate, etc
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
