<?php

namespace Sitefrog\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Sitefrog\Context;
use Sitefrog\Facades\RouteManager as RouteManagerFacade;
use Sitefrog\Facades\ComponentManager as ComponentManagerFacade;
use Sitefrog\Facades\WidgetManager as WidgetManagerFacade;
use Sitefrog\Http\RouteManager;
use Sitefrog\Modules\ModuleManager;
use Sitefrog\View\AssetManager;
use Sitefrog\View\ComponentManager;
use Sitefrog\View\Components\Assets;
use Sitefrog\View\Components\Form\FieldWrapper;
use Sitefrog\View\Components\Form\Form;
use Sitefrog\View\Components\Form\Submit;
use Sitefrog\View\Components\Grid\Block;
use Sitefrog\View\Components\Grid\Children;
use Sitefrog\View\Components\Grid\Container;
use Sitefrog\View\Components\Grid\Content;
use Sitefrog\View\Components\Grid\ContentMain;
use Sitefrog\View\Components\Grid\Grid;
use Sitefrog\View\Components\Grid\Row;
use Sitefrog\View\Components\Table\Table;
use Sitefrog\View\Components\WidgetWrapper;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\FormManager;
use Sitefrog\View\LayoutManager;
use Sitefrog\View\MenuManager;
use Sitefrog\View\PageData;
use Sitefrog\View\RedirectManager;
use Sitefrog\View\Themes\ThemeManager;
use Sitefrog\View\WidgetManager;
use Sitefrog\View\Widgets\Menu;

class SitefrogServiceProvider extends ServiceProvider {

    private ModuleManager $moduleManager;

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->loadHelpers();
        $this->registerFacades();
        $this->loadBase();
        $this->loadComponents();
        $this->loadWidgets();
        $this->loadModules();
    }

    private function registerFacades()
    {
        $this->app->bind('context', Context::class);
        $this->app->bind('page-data', PageData::class);

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

    private function loadComponents()
    {
        ComponentManagerFacade::register('assets', Assets::class);

        ComponentManagerFacade::register('widget-wrapper', WidgetWrapper::class);

        ComponentManagerFacade::register('form', Form::class);
        ComponentManagerFacade::register('form.input', Input::class);
        ComponentManagerFacade::register('form.field-wrapper', FieldWrapper::class);
        ComponentManagerFacade::register('form.submit', Submit::class);

        ComponentManagerFacade::register('grid', Grid::class);
        ComponentManagerFacade::register('grid.content', Content::class);
        ComponentManagerFacade::register('grid.content-main', ContentMain::class);
        ComponentManagerFacade::register('grid.block', Block::class);
        ComponentManagerFacade::register('grid.container', Container::class);
        ComponentManagerFacade::register('grid.row', Row::class);
        ComponentManagerFacade::register('grid.children', Children::class);

        ComponentManagerFacade::register('table', Table::class);
    }

    private function loadWidgets()
    {
        WidgetManagerFacade::register('sitefrog.menu', Menu::class);
    }

    private function loadModules()
    {
        $this->moduleManager = $this->app->make(ModuleManager::class);
        $this->moduleManager->load();

        foreach ($this->moduleManager->getModules() as $name => $module) {
            if ($module->hasViews) {
                $this->loadViewsFrom($module->getViewsPath(), $module->getNamespace());
            }

            if ($module->hasTranslations) {
                $this->loadTranslationsFrom($module->getTranslationsPath(), $module->getNamespace());
            }
        }
    }

}
