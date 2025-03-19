<?php

namespace Sitefrog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sitefrog\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class SetContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $context): Response
    {
        Context::set($context);

        return $next($request);
    }

}
