<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class MenuManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menu-manager';
    }
}
