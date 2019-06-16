<?php

namespace App\Http\Middleware;

use Closure;

class AdminGuest
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
        $admin_session = $request->session()->get('admin_authentication');
        if(!empty($admin_session)){
            return redirect('admin');
        }
        return $next($request);
    }
}
