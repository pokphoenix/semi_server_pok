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
    public function postLogout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return BF::result(true, "", 'Logout');
    }

    public function postChangepass(Request $request)
    {
        $input = BF::decodeInput($request);
        $rules = array(
            'oldPass' => 'required|alphaNum|min:3',
            'newPass' => 'required|alphaNum|min:3',
            'confirmPass' => 'required|alphaNum|min:3',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        } else {
            $data = [];
            $data['oldPass'] = $input['oldPass'] ;
            $data['newPass'] = $input['newPass'] ;
            $data['confirmPass'] = $input['confirmPass'] ;
            return BF::result(true, $data, 'ChangePass');
        }
    }

    public function Login(Request $request)
    {
        $input = BF::decodeInput($request->getContent());
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
                $today = Carbon::today()->timestamp ;
                $tomorrow = Carbon::tomorrow()->timestamp ;
                $token = array(
                    "iss" => $domain,
                    "aud" => $domain,
                    "iat" => $today,
                    "exp" => $tomorrow,
                    'name' => Auth::user()->name ,
                    'id' => $userId
                );

                $jwt = JWT::encode($token, getenv('APP_KEY'));

                $data = [
                    "token" => $jwt
                ];
                return BF::result(true, $data, 'login');
            } else {
                return BF::result(false, "Error!! Username or Password Incorrect. \nPlease try again.");
            }

        }
    }
    
}
