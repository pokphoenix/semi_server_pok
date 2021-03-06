<?php

namespace App\Http\Middleware;


use App\Models\PermRole;
use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Support\Facades\Request;
use Session;
use BF;
use Auth;
use Input;
use \Firebase\JWT\JWT;

class PermissionMiddleware
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
        $user = $request->user();
        if(empty($user)) {
            Auth::logout();
            return response(BF::result(false, 'permission denied.'), 401);
        }
        $roleId = $user->role_id ;
        $url = $request->url();
        $seg = explode('/', $url);
        if($seg[4]=='public') {
            $base = $seg[6];
            if(isset($seg[7])) $base2 = $seg[7];
        } else {
            $base = $seg[5];
            if(isset($seg[6])) $base2 = $seg[6];
        }
        $last = array_pop($seg);

        $permName = "$base";

        if (!empty($permName)){
            // check permission user
            try {
                $permission = PermRole::join('permissions', 'perm_role.permission_id', '=', 'permissions.id')
                    ->select('perm_role.id','permissions.name')
                    ->where('perm_role.role_id', '=', $roleId)
                    ->where('permissions.name', 'like','%' . $permName . '%' )
                    ->get();
                if(empty($permission)){
                    return response(BF::result(false, 'permission denied.'), 403);
                }
                $setPerm = [];
                foreach ($permission as $p){
                    $setPerm[] = $p->name ;
                }

                Session::set('perm', $setPerm);

            } catch ( \Illuminate\Database\QueryException $e) {
                return response(BF::result(false, $e->getMessage()), 401);
            }
        }

        $permSearch = "" ;
        if($request->isMethod('get')) {
            if(is_numeric($last)) {
                $permSearch = "$base.view";
            } else{
                if($base!=$last){
                    $permSearch = "$base.$last";
                }
            }
        } else if($request->isMethod('post')) {
            $permSearch = "$base.create";
        } else if($request->isMethod('patch')) {
            $permSearch = "$base.edit";
        } else if($request->isMethod('delete')) {
            $permSearch = "$base.edit";
        }
        
        if (!empty($permSearch) && (!in_array($permSearch, $setPerm)) ){
            return response(BF::result(false, 'permission denied.'), 403);
        }

        return $next($request);
    }
}
