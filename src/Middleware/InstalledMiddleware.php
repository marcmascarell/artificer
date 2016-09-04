<?php

namespace Mascame\Artificer\Middleware;

use Closure;
use Mascame\Artificer\InstallServiceProvider;

class InstalledMiddleware
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
        if (InstallServiceProvider::isInstalled()) {
            abort(404);
        }

        return $next($request);
    }
}
