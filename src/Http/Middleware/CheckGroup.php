<?php

namespace Sitefrog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGroup
{

    public function handle(Request $request, Closure $next, int | string $group_ids): Response
    {
        $group_ids = explode(';', $group_ids); // todo
        $user = auth()->user();
        if (!$user) {
            return redirect(route(config('sitefrog.system_routes.login'), ['redirect' => $request->getRequestUri()]));
        }

        if (!in_array($user->group_id, $group_ids)) {
           // return abort(403);
        }

        return $next($request);
    }

}
