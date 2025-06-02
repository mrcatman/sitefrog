<?php

namespace Sitefrog\View\Components\Grid;

use Illuminate\Support\Collection;
use Sitefrog\View\Component;
use Sitefrog\Facades\ComponentManager;

class Grid extends Component
{
    const SUBSTITUTE_PARAM_PREFIX = '$$';
    const SUBSTITUTE_TRANSLATION_PREFIX = '__';

    public function __construct(
        private ?string $file = null,
        public array | Collection | null $layout = null,
        public array | Collection | null $params = null,
    )
    {
        if (!empty($this->file)) {
            $this->loadFromFile();
        } else if (empty($this->layout)) {
            throw new \Exception('Specify either layout or file for grid');
        } else {
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
            if (is_array($value)) {
                $this->substituteParams($value);
            } else {
                if (str_starts_with($value, self::SUBSTITUTE_PARAM_PREFIX)) {
                    $paramName = ltrim($value, self::SUBSTITUTE_PARAM_PREFIX);
                    $params[$key] = $this->getParamValue($paramName);
                }

                if (str_starts_with($value, self::SUBSTITUTE_TRANSLATION_PREFIX)) {
                    $translationKey = ltrim($value, self::SUBSTITUTE_TRANSLATION_PREFIX);
                    $params[$key] = __($translationKey);
                }
            }
        }
    }

    private function getParamValue(string $paramName)
    {
        $paramPath = explode('.', $paramName);
        $paramValue = $this->params;
        foreach ($paramPath as $paramPathFragment) {
            if ((is_array($paramValue) && !isset($paramValue[$paramPathFragment])) || (!is_array($paramValue) && !isset($paramValue->$paramPathFragment))) {
                throw new \Exception("Param $paramName not found for grid template $this->file");
            }
            $paramValue = is_array($paramValue) ? $paramValue[$paramPathFragment] : $paramValue->$paramPathFragment;
        }
        return $paramValue;
    }

    private function configToComponent($config)
    {
        $params = !empty($config['params']) ? $config['params'] : [];

        $childrenPaths = ['children', 'fields']; // todo: rewrite
        foreach ($childrenPaths as $path) {
            if (isset($config[$path])) {
                $children = collect(!empty($config[$path]) ? $config[$path] : [])->map(function ($childConfig) {
                    return $this->configToComponent($childConfig);
                });
                $params[$path] = $children;
            }
        }

        $this->substituteParams($params);
        return ComponentManager::makeInstance($config['component'], $params);
    }

    public function loadFromFile()
    {
        $file = $this->findViewFile();
        $grid = json_decode(file_get_contents($file), 1); // todo: check version, validate, etc
        $layout = collect($grid['layout'])->map(function($config) {
            return $this->configToComponent($config);
        });

        $this->layout = $layout;
    }

    public static function getTemplate(): string
    {
        return 'sitefrog::components.grid';
    }

    public function getChildren(): array|Collection|null
    {
        return $this->layout;
    }
}
