<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class PageData extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'page-data';
    }
}
