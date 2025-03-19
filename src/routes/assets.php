<?php

$router->get('/_assets/{key}', function($key) {
    $assets = Cache::get('sitefrog::assets-'.$key);
    if (!$assets) {
        return abort(404);
    }
    return $assets;
})->name('sitefrog::assets');
