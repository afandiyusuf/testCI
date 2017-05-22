<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle localization
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('lan'))
            \App::setlocale($request->input('lan'));

        return $next($request);
    }
}