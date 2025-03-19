<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class ComponentManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'component-manager';
    }
}
