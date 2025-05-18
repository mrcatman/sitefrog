<?php

namespace Sitefrog\Providers;

use Illuminate\Support\ServiceProvider;
use Sitefrog\Context;
use Sitefrog\Http\RouteManager;
use Sitefrog\Permissions\PermissionManager;
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

class FacadesServiceProvider extends ServiceProvider {


    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->registerFacades();
        $this->registerSingletons();
    }

    private function registerFacades()
    {
        $this->app->bind('context', Context::class);
        $this->app->bind('page-data', PageData::class);
        $this->app->bind('page', Page::class);
        $this->app->bind('component-manager', ComponentManager::class);
        $this->app->bind('widget-manager', WidgetManager::class);
        $this->app->bind('form-manager', FormManager::class);
        $this->app->bind('layout-manager', LayoutManager::class);
        $this->app->bind('redirect-manager', RedirectManager::class);
        $this->app->bind('asset-manager', AssetManager::class);
        $this->app->bind('route-manager', RouteManager::class);
        $this->app->bind('theme-manager', ThemeManager::class);
    }

    private function registerSingletons()
    {
        $this->app->singleton(MenuManager::class, function () {
            return new MenuManager();
        });

        $this->app->singleton(PermissionManager::class, function () {
            return new PermissionManager();
        });
    }


}
