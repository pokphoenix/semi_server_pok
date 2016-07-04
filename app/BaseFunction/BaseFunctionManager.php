<?php namespace App\BaseFunction;

use App\BaseFunction\BaseFunctionManager as BF;
use Auth;
use \Firebase\JWT\JWT;


class BaseFunctionManager {

    public function doSomething()
    {
        echo 'Do something!';
    }

    public static function result($success = true, $message) {
        if($success) {
            return ['status' => 'success', 'datas' => $message];
        }
        return ['status' => 'error', 'message' => $message];
    }

    public static function authLoginFail($input) {
        $userId = $input['userid'] ;
        $auth = Auth::loginUsingId($userId);
        if (!$auth){
            return BF::result(false, "Error!! ไม่พบไอดีนี้ในระบบค่ะ!");
        }
        return false ;
    }

    public static function decodeInput($request) {
        $jwt = $request->getContent() ;
        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($jwt, getenv('APP_KEY') , array('HS256'));
        $decoded = (array)$decoded ;



        return (array)$decoded['data'];
    }

}