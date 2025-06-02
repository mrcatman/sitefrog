<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sitefrog\Exceptions\OverwriteResponseException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\ErrorException $e, $request) {
            if ($overwriteResponse = \Sitefrog\Facades\PageData::getOverwriteResponse()) {
                return response($overwriteResponse);
            }
            if (!$request->isMethod('get')) {
                return response((new \Sitefrog\View\Components\Error($e))->tryRender());
            }
        });
        $exceptions->render(function(OverwriteResponseException $e) {
            return response($e->getMessage());
        });
    })->create();
