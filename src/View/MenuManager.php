<?php

namespace Sitefrog\View;

use Sitefrog\View\Menu\Menu;

class MenuManager
{
    private $menus = [];

    public function __construct()
    {
        $this->menus['admin'] = new Menu();
    }

    public function register(string $name, Menu $menu)
    {
        $this->menus[$name] = $menu;
    }

    public function get(string $name): ?Menu
    {
        return isset($this->menus[$name]) ? $this->menus[$name] : null;
    }

    public function addItems(string $name, array $items)
    {
        $menu = $this->get($name);
        if (!$menu) {
            return;
        }
        $menu->addItems($items);
    }

}
