<?php
namespace Sitefrog\Modules;

use Sitefrog\Permissions\PermissionManager;
use Sitefrog\View\MenuManager;
use Sitefrog\View\RepositoryManager;
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
        private RepositoryManager $repositoryManager,
        private PermissionManager $permissionManager,

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

    protected function registerPermission($group, $label, $name, $defaults = [])
    {
        $this->permissionManager->register($this->getNamespace(false).'.'.$group, $label, $name, $defaults);
    }

    protected function registerResourcePermissions($resource, $labels_prefix = null, $except = [], $override_defaults = [])
    {
        if (!$labels_prefix) {
            $labels_prefix = $this->getNamespace().'::permissions.'.$resource;
        }
        $this->permissionManager->registerResource($this->getNamespace(false).'.'.$resource, $labels_prefix, $except, $override_defaults);
    }

    protected function registerRepository($resource, $repository)
    {
        $this->repositoryManager->register($resource, $repository);
    }

    protected function registerDefaultRepository($resource, array $params)
    {
        if (isset($params['permissions_prefix'])) {
            $params['permissions_prefix'] = $this->getNamespace(false).'.'.$params['permissions_prefix'];
        }
        $this->repositoryManager->registerDefault($resource, $params);
    }


}
