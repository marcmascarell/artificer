<?php

namespace Mascame\Artificer\Middleware;

use Closure;
use Mascame\Artificer\Artificer;

class PermissionGuard
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
        $action = Artificer::getCurrentAction();

        if ($action) {
            $roles = Artificer::modelManager()->current()->settings()->getOption('roles', []);

            if (isset($roles[$action])
                && ! empty($roles[$action])
                && ! Artificer::auth()->user()->hasAnyRole($roles[$action])) {
                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
