<?php

namespace Sitefrog\Providers;

use Illuminate\Support\ServiceProvider;
use Sitefrog\Facades\ComponentManager as ComponentManagerFacade;
use Sitefrog\Permissions\DefaultRoles;
use Sitefrog\Permissions\PermissionManager;
use Sitefrog\View\Components\Assets;
use Sitefrog\View\Components\Box;
use Sitefrog\View\Components\Button;
use Sitefrog\View\Components\Dropdown;
use Sitefrog\View\Components\Form\Builder\FieldBuilders\InputBuilder;
use Sitefrog\View\Components\Form\Builder\FormBuilderAddField;
use Sitefrog\View\Components\Form\Builder\FormBuilderComponent;
use Sitefrog\View\Components\Form\FormComponent;
use Sitefrog\View\Components\Head;
use Sitefrog\View\Components\ModalsContainer;
use Sitefrog\View\Components\WidgetWrapper;
use Sitefrog\View\Form\Builder\FormBuilder;

class FormBuilderServiceProvider extends ServiceProvider {

    private FormBuilder $formBuilder;

    public function __construct(
        $app,
    )
    {
        parent::__construct($app);
        $this->formBuilder = new FormBuilder();
    }

    public function boot(): void {
        $this->registerSingleton();
        $this->registerFieldBuilders();
        $this->registerPermissions();
        $this->registerComponents();
    }


    private function registerFieldBuilders()
    {
       $this->formBuilder->registerFieldBuilder(InputBuilder::class);
    }

    private function registerSingleton()
    {
        $this->app->singleton(FormBuilder::class, function () {
            return $this->formBuilder;
        });
    }

    private function registerPermissions()
    {
        $permissionManager = $this->app->make(PermissionManager::class);
        $permissionManager->registerGroup('form-builder', 'Form builder'); // todo lang
        $permissionManager->register('form-builder', 'Create & edit custom forms', 'forms', [
            DefaultRoles::SUPERADMIN->value
        ]);
    }
    private function registerComponents()
    {
        ComponentManagerFacade::register('form.builder', FormBuilderComponent::class);
        ComponentManagerFacade::register('form.builder.add-field', FormBuilderAddField::class);
    }
}
