<?php

namespace Sitefrog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sitefrog\Facades\WidgetManager;
use Symfony\Component\HttpFoundation\Response;

class LoadWidgets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        WidgetManager::load($request);

        return $next($request);
    }

}
