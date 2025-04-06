<?php

namespace Sitefrog\Http;
use Illuminate\Container\Container;
use Illuminate\Routing\Router;
use Sitefrog\Facades\Context;
use Sitefrog\Http\Middleware\SetContext;

class RouteManager {

    public function __construct(
        private Container $app,
        private Router $router
    ) {}

    public function registerWebRoutes($fn, $context = null, $options = [])
    {
        if (!$context) {
            $context = Context::default();
        }

        $prefix = Context::getParam('routes_prefix', $context, '');
        $this->router->group([
            'prefix' => $prefix,
            'middleware' => [
                'web',
                SetContext::class.':'.$context,
                ...config('sitefrog.middleware.web', []),
                ...Context::getParam('middleware', $context, [])
            ],
            ...$options
        ], function () use ($fn) {
            $fn($this->router);
        });
    }

    public function registerServiceRoutes($fn)
    {
        $this->router->group([
            'middleware' => [
                ...config('sitefrog.middleware.service', []),
            ]
        ], function () use ($fn) {
            $fn($this->router);
        });
    }

    public function resource(Router $router, string $controller, string $name, string $url)
    {
        $router->group([
            'prefix' => $url,
        ], function() use ($router, $controller, $name) {
            $router->name($name . '.index')->get('', [$controller, 'index']);
            $router->name($name . '.edit')->any('{id}', [$controller, 'edit']);
            $router->name($name . '.create')->any('create', [$controller, 'create']);
            $router->name($name . '.delete')->any('{id}/delete', [$controller, 'delete']);
        });

    }

}
