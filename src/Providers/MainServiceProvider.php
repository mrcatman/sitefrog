<?php

namespace Sitefrog\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Sitefrog\Facades\RouteManager;
use Sitefrog\Modules\ModuleManager;

class MainServiceProvider extends ServiceProvider {


    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->loadBase();
        $this->loadModules();
    }

    private function loadBase()
    {
        $this->loadTranslationsFrom(base_path('src/Resources/lang'), 'sitefrog');
        $this->loadViewsFrom(base_path('src/Resources/views'), 'sitefrog');
        $this->loadMigrationsFrom(base_path('src/Database/Migrations'));

        RouteManager::registerServiceRoutes(function(Router $router) {
            require_once base_path('src/routes/assets.php');
        });
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
