<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class RedirectManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'redirect-manager';
    }
}
