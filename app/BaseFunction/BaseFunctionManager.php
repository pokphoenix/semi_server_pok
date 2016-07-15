<?php namespace App\BaseFunction;

use App\BaseFunction\BaseFunctionManager as BF;
use Auth;
use \Firebase\JWT\JWT;
use \Illuminate\Http\Request ;
use Session;

class BaseFunctionManager {

    public function doSomething()
    {
        echo 'Do something!';
    }


    public static function TestSetting($method) {
        $key = [
            'fixToken' => false,          //--- for return fix token  (no generate new token)
            'userTypeNotSave' => false,    //--- setting don't save usertype for test in client
            'userTypeAlert' => false,       //--- for check alert popup
            'userTypeNotDelete' => false
        ] ;
        return $key[$method] ;
    }


    public static function result($success = true, $message, $action = false) {
        if($success) {
            $res = ['status' => 'success', 'data' => $message];
            if($action) $res['action'] = $action;
            return $res;
        }
        return ['status' => 'error', 'data' => ['error' => $message]];
    }

    public static function decodeInput($request) {
//        JWT::$leeway = 60; // $leeway in seconds
        try {
            $decoded = JWT::decode($request, env('APP_KEY'), array('HS256'));
        } catch ( \Firebase\JWT\ExpiredException $e) {
            return response(BF::result(false, $e->getMessage()), 401);
        }
        $decoded = (array)$decoded ;
        return (array)$decoded['payload'];
    }

    public static function getPermission($perm) {
        return isset(Session::get('perm')[$perm]);
    }

    public static function dataTable($tbData, $countFilter, $countTotal, $perm) {
        return [
            'tbData' => $tbData,
            'recordsFiltered' => $countFilter,
            'recordsTotal' => $countTotal,
            'perm' => $perm,
        ];


    }
    

}