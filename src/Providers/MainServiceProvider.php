<?php

namespace Sitefrog\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Sitefrog\Context;
use Sitefrog\Facades\RouteManager as RouteManagerFacade;
use Sitefrog\Http\RouteManager;
use Sitefrog\Modules\ModuleManager;
use Sitefrog\View\AssetManager;
use Sitefrog\View\ComponentManager;
use Sitefrog\View\FormManager;
use Sitefrog\View\LayoutManager;
use Sitefrog\View\MenuManager;
use Sitefrog\View\Page;
use Sitefrog\View\PageData;
use Sitefrog\View\RedirectManager;
use Sitefrog\View\Themes\ThemeManager;
use Sitefrog\View\WidgetManager;

class MainServiceProvider extends ServiceProvider {


    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->loadHelpers();
        $this->registerFacades();
        $this->loadBase();
        $this->loadModules();
    }

    private function registerFacades()
    {
        $this->app->bind('context', Context::class);
        $this->app->bind('page-data', PageData::class);
        $this->app->bind('page', Page::class);
        $this->app->bind('menu-manager', MenuManager::class);
        $this->app->bind('component-manager', ComponentManager::class);
        $this->app->bind('widget-manager', WidgetManager::class);
        $this->app->bind('form-manager', FormManager::class);
        $this->app->bind('layout-manager', LayoutManager::class);
        $this->app->bind('redirect-manager', RedirectManager::class);
        $this->app->bind('asset-manager', AssetManager::class);
        $this->app->bind('route-manager', RouteManager::class);
        $this->app->bind('theme-manager', ThemeManager::class);
    }

    private function loadBase()
    {
        $this->loadTranslationsFrom(base_path('src/Resources/lang'), 'sitefrog');
        $this->loadViewsFrom(base_path('src/Resources/views'), 'sitefrog');
        $this->loadMigrationsFrom(base_path('src/Database/Migrations'));

        RouteManagerFacade::registerServiceRoutes(function(Router $router) {
            require_once base_path('src/routes/assets.php');
        });
    }

    private function loadHelpers()
    {
        require_once base_path('src/Helpers/functions.php');
    }

    private function loadModules()
    {
        $moduleManager = $this->app->make(ModuleManager::class);
        $moduleManager->load();

        foreach ($moduleManager->getModules() as $name => $module) {
            if ($module->hasTranslations) {
                $this->loadTranslationsFrom($module->getTranslationsPath(), $module->getNamespace());
            }
            if ($module->hasViews) {
                $this->loadViewsFrom($module->getViewsPath(), $module->getNamespace());
            }

            $module->load();
        }
    }

}
