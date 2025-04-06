<?php

namespace Sitefrog\View;

class HTMX
{
    public static function isHtmxRequest()
    {
        return request()->header('hx-request') === 'true';
    }

    public static  function isFormRequest()
    {
        return self::isHtmxRequest() && request()->has('_sf_form');
    }

    public static function isModalRequest()
    {
        return self::isHtmxRequest() && request()->has('_sf_modal');
    }
}
