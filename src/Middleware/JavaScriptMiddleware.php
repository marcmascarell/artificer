<?php

namespace Mascame\Artificer\Middleware;

use Closure;

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
        \Blade::directive('phpToJS', function () {
            return "<?php echo \Mascame\Artificer\Support\JavaScript::transform(); ?>";
        });

        return $next($request);
    }
}
