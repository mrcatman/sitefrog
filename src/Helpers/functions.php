<?php

if (!function_exists('sitefrogRoute')) {
    function sitefrogRoute($module, $name, $parameters = [], $absolute = true)
    {
        return route("sitefrog.$module::$name", $parameters, $absolute);
    }

}
