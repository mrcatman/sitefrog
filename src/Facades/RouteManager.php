<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class RouteManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'route-manager';
    }
}
