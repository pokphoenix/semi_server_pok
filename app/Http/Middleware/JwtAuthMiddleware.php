<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use BF;
use Auth;

class JwtAuthMiddleware
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

        dd($request) ;
        exit();

        $input = BF::decodeInput($request);

        return $input ;

        return $next($request);
    }
}
