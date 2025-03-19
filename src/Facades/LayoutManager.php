<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class LayoutManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'layout-manager';
    }
}
