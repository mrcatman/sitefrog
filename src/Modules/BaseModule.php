<?php
namespace Sitefrog\Modules;

use Sitefrog\Facades\MenuManager;
use Sitefrog\Facades\WidgetManager;
use Sitefrog\Facades\RouteManager;

class BaseModule {

    public $name;

    public $hasViews = false;
    public $hasTranslations = false;

    public function load(): void {}

    public function getPath()
    {
        return base_path("Modules/$this->name");
    }

    public function getViewsPath()
    {
        return $this->getPath()."/Resources/views";
    }

    public function getTranslationsPath()
    {
        return $this->getPath()."/Resources/lang";
    }

    public function getNamespace()
    {
        return 'sitefrog.'.mb_strtolower($this->name);
    }

    public function getRouteName($name)
    {
        return $this->getNamespace().'::'.$name;
    }

    protected function registerRoutes($fn, $context = null)
    {
        RouteManager::registerWebRoutes($fn, $context, [
            //'name' => $this->getNamespace().'.'
        ]);
    }

    protected function registerWidget(string $widgetName, string $class)
    {
        WidgetManager::register($this->getNamespace()."::$widgetName", $class);
    }

    protected function registerAdminMenuItems(array $items)
    {
        MenuManager::addItems('admin', $items);
    }


}
