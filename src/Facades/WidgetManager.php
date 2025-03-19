<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class WidgetManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'widget-manager';
    }
}
