<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Carbon\Carbon;
use Input;
use BF;
use Validator;
use Auth;
use Session;
use Tymon\JWTAuth\Exceptions\JWTException;
use \Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function getLogin()
    {
        $rules = array(
            'username' => 'required',
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        } else {
            $username = Input::get('username') ;
            $password = Input::get('password') ;
            if (!preg_match("/@/", $username)) {
                $search_column = 'name';
            } else {
                $search_column = 'email';
            }
            if (Auth::attempt([$search_column => $username, 'password' => $password], Input::has('remember'))) {
                $userId = Auth::user()->id;
//                Auth::loginUsingId($userId);
                $data = [
                    'name' => Auth::user()->name ,
                    'id' => $userId
                ];
                return BF::result(true, ['action' => 'create', 'data' => $data]);
            } else {
                return BF::result(false, "Error!! Username or Password Incorrect. \nPlease try again.");
            }
        }
    }

    public function getTest(){
//        $input = Input::all() ;
//        $rules = array(
//            'userid' => 'required|Numeric'
//        );
//        $validator = Validator::make($input, $rules);
//        if ($validator->fails()) {
//            return BF::result(false, $validator->messages()->first());
//        } else {
//            $data = [];
//            $auth = BF::authLoginFail($input);
//            if($auth) {
//                return $auth ;
//            }
//            $data["created_by"] = Auth::user()->name ;
//            return BF::result(true, ['action' => 'create', 'data' => $data]);
//        }

        if (starts_with(Request::root(), 'http://'))
        {
            $domain = substr (Request::root(), 7); // $domain is now 'www.example.com'
        }

        $tomorrow = Carbon::now()->addDay();

        return $tomorrow ;

        $token = array(
            "iss" => $domain,
            "aud" => $domain,
            "iat" => $tomorrow,
            "nbf" => $tomorrow,
            'name' => Auth::user()->name ,
            'id' => $userId
        );

    }

    public function getLogout()
    {
        $input = Input::all() ;
        $rules = array(
            'userid' => 'required|Numeric'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        } else {
            $data = [];
            $auth = BF::authLoginFail($input);
            if($auth) {
                return $auth ;
            }
            Auth::logout();
            Session::flush();
            return BF::result(true, ['action' => 'Logout']);
        }
    }

    public function postLogout(Request $request)
    {

        $token = $this->auth->setRequest($request)->getToken();
        $user = $this->auth->authenticate($token);

        return "Hello"+$user ;

        $rules = array(
            'passdata' => 'required|String'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        } else {
            $data = [];
            $auth = BF::authLoginFail($input);
            if($auth) {
                return $auth ;
            }
            Auth::logout();
            Session::flush();
            return BF::result(true, ['action' => 'Logout']);
        }
    }

    public function Login(Request $request)
    {
        $input = BF::decodeInput($request);
        $rules = array(
            'username' => 'required',
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        } else {
            //---Logic auth with username or email
            $username = $input['username'] ;
            $password = $input['password'] ;
            if (!preg_match("/@/", $username)) {
                $search_column = 'name';
            } else {
                $search_column = 'email';
            }


            if (Auth::attempt([$search_column => $username, 'password' => $password], Input::has('remember'))) {
                $userId = Auth::user()->id;
//                Auth::loginUsingId($userId);

                $domain = $_SERVER['HTTP_HOST'];
                $tomorrow = Carbon::now()->timestamp ;
                $token = array(
                    "iss" => $domain,
                    "aud" => $domain,
                    "iat" => $tomorrow,
                    "nbf" => $tomorrow,
                    'name' => Auth::user()->name ,
                    'id' => $userId
                );

                $jwt = JWT::encode($token, getenv('APP_KEY'));

                $data = [
                    "token" => $jwt
                ];

                return BF::result(true, ['action' => 'create', 'data' => $data ]);
            } else {
                return BF::result(false, "Error!! Username or Password Incorrect. \nPlease try again.");
            }

            $credentials = [] ;
            $credentials[$search_column] = $username ;
            $credentials['password'] = $password ;
            try{
                if(! $token = JWTAuth::attempt($credentials)){
                    return BF::result(false, $this->response->errorUnauthorized() );
                }
            } catch (JWTException $ex){
                return BF::result(false, $this->response->errorInternal() );
            }

            $return = [
                "token" => compact('token')
            ] ;

            return BF::result(true, ['action' => 'login', 'data' => $return]);


//            if (Auth::attempt([$search_column => $username, 'password' => $password], Input::has('remember'))) {
//                $userId = Auth::user()->id;
////                Auth::loginUsingId($userId);
//                $data = [
//                    'name' => Auth::user()->name ,
//                    'id' => $userId
//                ];
//
//                $data = [
//                    "token" => "1",
//                    "name" => "test",
//                    "pass" => "1234",
//                    "desc" => "Hello World"
//                ];
//
//                $jwt = JWT::encode($data, getenv('APP_KEY'));
//
//                return BF::result(true, ['action' => 'create', 'data' => $data]);
//            } else {
//                return BF::result(false, "Error!! Username or Password Incorrect. \nPlease try again.");
//            }
        }
    }
    
}
