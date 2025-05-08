<?php

namespace Sitefrog\View;

class HTMX
{
    public static function isHtmxRequest()
    {
        return request()->header('hx-request') === 'true';
    }

    public static function isFullReloadRequest()
    {
        return request()->header('hx-target') !== 'content';
    }

    public static  function isFormRequest()
    {
        return self::isHtmxRequest() && request()->form();
    }

    public static function isModalRequest()
    {
        return self::isHtmxRequest() && request()->modal();
    }

    public static function bodyAttributes()
    {
        if (config('sitefrog.htmx.boost')) {
            return [
                'hx-boost' => 'true',
            ];
        }
        return [];
    }
}
