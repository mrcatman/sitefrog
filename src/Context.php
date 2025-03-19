<?php
namespace Sitefrog;
use Sitefrog\Facades\AssetManager;
use Sitefrog\Facades\ThemeManager;

class Context {

    private $contexts;
    private $names;
    private string $default;
    private string $current;

    public function __construct()
    {
        $this->load();
    }

    private function load() {
        $this->contexts = config('sitefrog.contexts.list');
        $this->names = array_keys($this->contexts);

        $this->default = config('sitefrog.contexts.default');
        $this->current = $this->default;
    }

    public function default()
    {
        return $this->default;
    }

    public function current()
    {
        return $this->current;
    }

    public function set($context)
    {
        if (!in_array($context, $this->names)) {
            throw new \Exception("Unknown context: $context");
        }
        $this->current = $context;

        $this->update();
    }

    public function getParam($key, $context = null, $default = null)
    {
        if (!$context) {
            $context = $this->current;
        }

        return config('sitefrog.contexts.list.'.$context.'.' . $key, $default);
    }

    public function update()
    {
        AssetManager::loadGlobals();

        $theme = $this->getParam('theme');
        ThemeManager::set($theme);
    }

}
