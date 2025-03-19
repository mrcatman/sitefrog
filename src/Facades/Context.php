<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class Context extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'context';
    }
}
