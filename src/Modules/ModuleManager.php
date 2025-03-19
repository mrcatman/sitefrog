<?php
namespace Sitefrog\Modules;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;

class ModuleManager {

    private array $modules = [];

    public function __construct(
        private Container $app
    ) {
    }

    public function getModules()
    {
        return array_values($this->modules);
    }

    public function load(): void
    {
        $moduleNames = Storage::disk('modules')->directories();
        foreach ($moduleNames as $moduleName) {
            $module = $this->loadModule($moduleName);
            $this->modules[$moduleName] = $module;
        }
    }

    private function loadModule(string $name): BaseModule
    {
        $className = "Modules\\$name\\Module";
        $class = $this->app->make($className);
        $class->load();
        return $class;
    }



}
