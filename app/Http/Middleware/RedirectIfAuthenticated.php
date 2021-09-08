<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            \Log::debug("Check on Redirect if Authenticated Middleware", [
                'guard' => $guard,
                'guards' => $guards,
                'check' => Auth::guard($guard)->check(),
                'request' => $request->all()
            ]);
            if (Auth::guard($guard)->check()) {
                switch($guard){
                    case 'admin':
                        return redirect(route('adm.index'));
                        break;
                    default:
                        return redirect(RouteServiceProvider::HOME);
                        break;
                }
            }
        }

        return $next($request);
    }
}
