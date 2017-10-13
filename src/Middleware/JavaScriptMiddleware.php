<?php

namespace Mascame\Artificer\Middleware;

use Closure;
use Mascame\Artificer\Support\JavaScript;

class JavaScriptMiddleware
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
        JavaScript::sendDataToJS();

        return $next($request);
    }
}
