<?php
namespace Sitefrog\Modules;

use Sitefrog\Permissions\PermissionManager;
use Sitefrog\View\MenuManager;
use Sitefrog\View\WidgetManager;
use Sitefrog\Http\RouteManager;

class BaseModule {

    public $name;

    public $hasViews = false;
    public $hasTranslations = false;

    public function __construct(
        private MenuManager $menuManager,
        private WidgetManager $widgetManager,
        private RouteManager $routeManager,
        private PermissionManager $permissionManager
    )
    {
    }

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

    public function getNamespace($full = true)
    {
        return ($full ? 'sitefrog.' : '').mb_strtolower($this->name);
    }

    public function getRouteName($name)
    {
        return $this->getNamespace().'::'.$name;
    }

    protected function registerRoutes($fn, $context = null)
    {
        $this->routeManager->registerWebRoutes($fn, $context, [
            //'name' => $this->getNamespace().'.'
        ]);
    }

    protected function registerWidget(string $widgetName, string $class)
    {
        $this->widgetManager->register($this->getNamespace()."::$widgetName", $class);
    }

    protected function registerAdminMenuItems(array $items)
    {
        $this->menuManager->addItems('admin', $items);
    }

    protected function registerResourcePermissions($group, $labels_prefix = null, $except = [], $override_defaults = [])
    {
        if (!$labels_prefix) {
            $labels_prefix = $this->getNamespace().'::permissions.'.$group;
        }
        $this->permissionManager->registerResource($this->getNamespace(false).'.'.$group, $labels_prefix, $except, $override_defaults);
    }


}
