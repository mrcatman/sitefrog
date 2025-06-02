<?php

namespace Sitefrog\View\Widgets;

use Illuminate\View\View;
use Sitefrog\View\Components\Form\Fields\Input;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Menu\Menu as MenuInstance;
use Sitefrog\View\MenuManager;
use Sitefrog\View\Widget;

class Menu extends Widget
{
    private ?MenuInstance $menu;

    public function __construct(
        private string $id,
        private MenuManager $menuManager,
    ) {}

    public function load()
    {
        $this->menu = $this->menuManager->get($this->id);
    }

    public function getConfig()
    {
        return new Form(
            'menu-widget-form',
            [
                new Input(
                    name: 'id',
                    label: 'menu id',
                    rules: ['required']
                ),
            ]
        );
    }

    public function render(): View | string
    {
        if (!$this->menu) {
            return '';
        }
        return view('sitefrog::widgets.menu', [
            'id' => $this->id,
            'items' => $this->menu->getItems(),
            'level' => 0
        ]);
    }
}
