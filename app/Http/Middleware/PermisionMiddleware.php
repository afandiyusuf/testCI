<?php

namespace App\Http\Middleware;

use Closure;

class PermisionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission)
    {
         if(\Auth::check()){
            if(\Auth::user()->can($permission)){
                return $next($request);
            }else{
                return \Redirect::to('/ops/'.$permission);
            }
        }
    }
}
