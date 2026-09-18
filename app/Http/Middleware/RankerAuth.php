<?php

namespace App\Http\Middleware;

use Closure;

class RankerAuth
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
        if (session()->has('rankersAuth') && session()->get('rankersAuth')){
            return $next($request);
        }

        return redirect('/rankers');

    }
}
