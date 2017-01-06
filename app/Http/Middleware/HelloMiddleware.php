<?php

namespace App\Http\Middleware;

use Closure;

class HelloMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (preg_match('/balrog$/i', $request->getRequestUri())) {
            return response('YOU SHALL NOT PASS!', 403);
        }

        return $next($request);
    }
}
