<?php
namespace Sitefrog\Facades;

use Illuminate\Support\Facades\Facade;
class AssetManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'asset-manager';
    }
}
