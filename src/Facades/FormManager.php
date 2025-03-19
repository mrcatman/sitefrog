<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class FormManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'form-manager';
    }
}
