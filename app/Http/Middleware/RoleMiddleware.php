<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        if(\Auth::check()){
            if(\Auth::user()->hasRole($role)){
                return $next($request);
            }
            else{
                return \Redirect::to('/ops/'.$role);
            }
        }

    }
}
