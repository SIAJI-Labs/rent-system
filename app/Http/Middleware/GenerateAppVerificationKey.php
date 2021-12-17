<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateAppVerificationKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Session::put('_randKey', generateRandomString(12));
        return $next($request);
    }
}
