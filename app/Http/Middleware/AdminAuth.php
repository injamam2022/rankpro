<?php

namespace App\Http\Middleware;

use App\Models\Administrator;
use Closure;

class AdminAuth
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
        $adminId = session()->get('adminAuth');
        if (is_array($adminId)) {
            $adminId = $adminId['id'] ?? null;
        }

        if ($adminId && Administrator::find($adminId)) {
            return $next($request);
        }

        session()->forget([
            'adminAuth',
            'adminName',
            'admmin_is_super',
            'admin_role_role_id',
            'admmin_subject_id',
        ]);

        return redirect('/webadmin');
    }
}
