<?php

namespace App\Http\Middleware;

use Closure;
use App\Session;

class CheckSessionKey
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
        if (Session::isASessionKey($request->session_key)) {
            return $next($request);
        } else {
            return redirect('/');
        }
    }
}
