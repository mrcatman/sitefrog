<?php

namespace Sitefrog\Providers;

use Illuminate\Support\ServiceProvider;
use Sitefrog\Facades\ComponentManager as ComponentManagerFacade;
use Sitefrog\Facades\WidgetManager as WidgetManagerFacade;
use Sitefrog\View\Components\Assets;
use Sitefrog\View\Components\Box;
use Sitefrog\View\Components\Button;
use Sitefrog\View\Components\Dropdown;
use Sitefrog\View\Components\Form\Fields\Checkbox;
use Sitefrog\View\Components\Form\Fields\Input;
use Sitefrog\View\Components\Form\Fields\Radio;
use Sitefrog\View\Components\Form\Fields\Select;
use Sitefrog\View\Components\Form\FieldWrapper;
use Sitefrog\View\Components\Form\FormComponent;
use Sitefrog\View\Components\Form\Submit;
use Sitefrog\View\Components\Grid\Block;
use Sitefrog\View\Components\Grid\Children;
use Sitefrog\View\Components\Grid\Container;
use Sitefrog\View\Components\Grid\Content;
use Sitefrog\View\Components\Grid\ContentMain;
use Sitefrog\View\Components\Grid\Grid;
use Sitefrog\View\Components\Grid\Row;
use Sitefrog\View\Components\Head;
use Sitefrog\View\Components\ModalsContainer;
use Sitefrog\View\Components\Table\TableComponent;
use Sitefrog\View\Components\WidgetWrapper;
use Sitefrog\View\Engines\GridEngine;
use Sitefrog\View\Widgets\Menu;

class ComponentsServiceProvider extends ServiceProvider {

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->registerComponents();
        $this->registerWidgets();
        $this->registerEngines();
    }


    private function registerComponents()
    {
        ComponentManagerFacade::register('assets', Assets::class);
        ComponentManagerFacade::register('head', Head::class);

        ComponentManagerFacade::register('box', Box::class);
        ComponentManagerFacade::register('modals-container', ModalsContainer::class);
        ComponentManagerFacade::register('dropdown', Dropdown::class);
        ComponentManagerFacade::register('button', Button::class);
        ComponentManagerFacade::register('widget-wrapper', WidgetWrapper::class);

        ComponentManagerFacade::register('form', FormComponent::class);
        ComponentManagerFacade::register('form.input', Input::class);
        ComponentManagerFacade::register('form.select', Select::class);
        ComponentManagerFacade::register('form.checkbox', Checkbox::class);
        ComponentManagerFacade::register('form.radio', Radio::class);

        ComponentManagerFacade::register('form.field-wrapper', FieldWrapper::class);

        ComponentManagerFacade::register('grid', Grid::class);
        ComponentManagerFacade::register('grid.content', Content::class);
        ComponentManagerFacade::register('grid.content-main', ContentMain::class);
        ComponentManagerFacade::register('grid.block', Block::class);
        ComponentManagerFacade::register('grid.container', Container::class);
        ComponentManagerFacade::register('grid.row', Row::class);
        ComponentManagerFacade::register('grid.children', Children::class);

        ComponentManagerFacade::register('table', TableComponent::class);
    }

    private function registerWidgets()
    {
        WidgetManagerFacade::register('sitefrog.menu', Menu::class);
    }

    private function registerEngines()
    {
        /** @var \Illuminate\View\Engines\EngineResolver $engineResolver */
        $engineResolver = $this->app->get('view.engine.resolver');
        $engineResolver->register('grid', function () {
            return new GridEngine();
        });

        /** @var \Illuminate\View\Factory $viewFactory */
        $viewFactory = $this->app->get('view');
        $viewFactory->addExtension('json', 'grid');
        $viewFactory->getFinder()->addExtension('json');
    }

}
