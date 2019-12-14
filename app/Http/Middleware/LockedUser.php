<?php

namespace App\Http\Middleware;


use Illuminate\Support\Facades\Auth;

class LockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, $next)
    {
        If (Auth::check()){
            if (Auth::user()->locked == 1) {
                return response('Не подтвержден номер телефона', 423);
            }
            else if (Auth::user()->locked == 2) {
                return response('Аккаунт забанен', 423);
            }
        }

        return $next($request);
    }
}
