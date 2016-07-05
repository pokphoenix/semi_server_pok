<?php

namespace App\Http\Middleware;


use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Support\Facades\Request;
use Session;
use BF;
use Auth;
use Input;
use \Firebase\JWT\JWT;

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
        $input = BF::decodeInput($request->getContent());
        $user = JWT::decode($input['token'], getenv('APP_KEY') , array('HS256'));
        $dateExpire = $user->exp ;
        $dateNow = (int) Carbon::now()->timestamp ;

        // check token expire
        if ($dateNow > (int)$dateExpire) {
            return response(BF::result(false, 'Token Expire.'), 401);
        }

        // check auth user
        try {
            $auth = Auth::loginUsingId($user->id);
            if($auth === NULL) {
                return response(BF::result(false, 'permission denied.'), 401);
            }
        } catch ( \Illuminate\Database\QueryException $e) {
            return response(BF::result(false, $e->getMessage()), 401);
        }

        return $next($request);
    }
}
