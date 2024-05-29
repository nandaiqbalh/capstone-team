<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role_id;

                switch ($role) {
                    case '01':
                        return redirect(RouteServiceProvider::HOME_ADMIN);
                    case '02':
                        return redirect(RouteServiceProvider::HOME_TIM_CAPSTONE);
                    case '03':
                        return redirect(RouteServiceProvider::HOME_MAHASISWA);
                    case '04':
                        return redirect(RouteServiceProvider::HOME_DOSEN);
                    default:
                        return redirect(RouteServiceProvider::HOME_MAHASISWA);
                }
            }
        }

        return $next($request);
    }
}
