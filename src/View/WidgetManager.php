<?php

namespace Sitefrog\View;
use Illuminate\Http\Request;
use Sitefrog\Facades\Context;
use Sitefrog\Facades\ComponentManager;
use Sitefrog\View\Components\Grid\Grid;

class WidgetManager
{
    private $areas;
    private $widgets;

    private $instances;

    public function __construct()
    {
        $this->areas = collect([
            'main' => [
                'aside' => [
                    [
                        'class' => 'sitefrog.auth::auth-block',
                        'params' => []
                    ],
                    [
                        'class' => 'sitefrog.auth::welcome',
                        'params' => []
                    ]
                ]
            ],
            'admin' => [
                'aside' => [
                    [
                        'class' => 'sitefrog.menu',
                        'params' => [
                            'id' => 'admin'
                        ]
                    ],
                ]
            ]
        ]);
        $this->widgets = collect([]);
        $this->instances = collect([]);
    }

    public function register($name, $class)
    {
        $this->widgets[$name] = $class;
        ComponentManager::register($name, $class);
    }


    public function getAreaGrid(string $area)
    {
        $context = Context::current();
        if (empty($this->instances[$context][$area])) {
            return null;
        }
        $widgets = $this->instances[$context][$area];

        return new Grid(
            layout: $widgets
        );
    }

    public function load(Request $request)
    {
        $context = Context::current();

        $this->instances[$context] = collect([]);
        foreach ($this->areas[$context] as $area => $widgets) {
            $this->instances[$context][$area] = collect([]);
            foreach ($widgets as $widget) {
                $instance = ComponentManager::makeInstance($widget['class'], $widget['params']);
                $instance->load();

                $this->instances[$context][$area]->push($instance);
            }
        }
    }

}
