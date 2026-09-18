<?php

namespace App\Http\Middleware;

use Closure;

class CounsellorAuth
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
        if (session()->has('counsellorAuth') && session()->get('counsellorAuth')){
            return $next($request);
        }

        return redirect('/counsellor');

    }
}
