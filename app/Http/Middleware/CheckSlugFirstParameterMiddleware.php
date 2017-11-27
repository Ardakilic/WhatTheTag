<?php namespace App\Http\Middleware;

use Closure;

class CheckSlugFirstParameterMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //Parameters return the variable name defined in route if not
        //set as Route::controller() which is now deprecated.
        $firstParam = $request->route()->parameters()['slug'];

        if (!preg_match("/^[0-9A-z-_]+$/", $firstParam)) {
            return response('Wrong input', 403);
        }

        return $next($request);

    }

}