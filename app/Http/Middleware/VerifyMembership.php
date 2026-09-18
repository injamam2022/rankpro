<?php

namespace App\Http\Middleware;
use Session;
use Illuminate\Support\Facades\Auth;


use Closure;

class VerifyMembership
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
        if(Auth::check()){
            return $next($request);
        }
        return redirect('/');
    }
}
