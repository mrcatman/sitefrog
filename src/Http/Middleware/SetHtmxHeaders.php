<?php

namespace Sitefrog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetHtmxHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($response->isRedirect() && request()->header('hx-request') === 'true') {
            $response->setStatusCode(201);
            $response->headers->remove('Location');
            $response->headers->set('HX-Location', $response->getTargetUrl());
        }

        return $response;
    }

}
