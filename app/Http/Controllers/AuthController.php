<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Input;
use BF;
use Validator;
use Auth;

class AuthController extends Controller
{

    public function getLogin()
    {
        $rules = array(
            'username'    => 'required', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );




        $validator = Validator::make(Input::all(), $rules);
        dd($validator);
        if ($validator->fails()) {
            return BF::result(false, $validator);
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
                Auth::loginUsingId($userId);

                $data = [
                    'name' => Auth::user()->name ,
                ];

                return BF::result(true, ['action' => 'create', 'data' => $data]);

            } else {
                return BF::result(false, "Error!! Username or Password Incorrect. \nPlease try again.");
            }

        }
    }

    public function getLogout()
    {
        // Todo Check User/Pass Here

        // If login correct
        Auth::logout();
        Session::flush();

        // Todo return something;
    }

    public function postChangePass()
    {
        $data = Input::all();
        $chkToken =  BF::checktoken($data['timestamp'],$data['token']) ;
        if(!$chkToken){
            return BF::result(false, 'token invalid');
        }

        if (empty($data['newPassword'])) {
            return BF::result(false, 'ไม่พบ รหัสผ่าน');
        }
        if (empty($data['newPasswordConfirm'])) {
            return BF::result(false, 'ไม่พบ ยืนยันรหัสผ่าน');
        }
        if (empty($data['email'])) {
            return BF::result(false, 'ไม่พบ อีเมล์');
        }

        try {
            if ($data['newPassword'] != $data['newPasswordConfirm']) {
                return BF::result(false, "กรุณากรอกพาสเวิดให้ตรงกัน");
            }
            $data['password'] = $data['newPassword'] ;

            $status = User::where('email', $data['email'])->first();
            if ($status === NULL) {
                return BF::result(false, 'ไม่พบ email นี้ในระบบ');
            }
            if ($data['oldPassword'] != $status->password) {
                return BF::result(false, "กรุณากรอก Old Password ให้ถูกต้องค่ะ");
            }
            unset($data["oldPassword"]);
            unset($data["newPassword"]);
            unset($data["newPasswordConfirm"]);
            unset($data["timestamp"]);
            unset($data["token"]);
            $statusUpdate = User::whereId($status->id)->update($data);
            if ($statusUpdate === NULL) {
                return BF::result(false, 'อัพเดทข้อมูลไม่สำเร็จ');
            }
            return BF::result(true, ['action' => 'create']);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return BF::result(false, "email นี้มีในระบบอยู่แล้วค่ะ");
            }
            return BF::result(false, $e->getMessage());
        }
    }

    public function postForgotPass()
    {
        $data = Input::all();

        $chkToken =  BF::checktoken($data['timestamp'],$data['token']) ;
        if(!$chkToken){
            return BF::result(false, 'token invalid');
        }
        if (empty($data['email'])) {
            return BF::result(false, 'ไม่พบ อีเมล์');
        }
        try {

            $data['newPassword'] = User::randomPassword();
            $data['password'] = sha1($data['newPassword']);
            $status = User::where('email', $data['email'])->first();
            if ($status === NULL) {
                return BF::result(false, 'ไม่พบ email นี้ในระบบ');
            }

            Mail::send('emails.forgotpassword', ['newpassword' => $data['newPassword']], function ($message) use ($data) {
                $message->subject('Fotgot Password!');
                $message->from('app.semicolon@gmail.com', 'Masterpiece Clinic');
                $message->to($data['email']); // Recipient address
            });

            if (count(Mail::failures()) > 0) {
                return BF::result(false, 'ส่งเมล์ไม่สำเร็จ');
            } else {

                unset($data["newPassword"]);
                unset($data["timestamp"]);
                unset($data["token"]);

                $statusUpdate = User::whereId($status->id)->update($data);
                if ($statusUpdate === NULL) {
                    return BF::result(false, 'อัพเดทข้อมูลไม่สำเร็จ');
                }
                return BF::result(true, ['action' => 'update']);
            }


        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return BF::result(false, "email นี้มีในระบบอยู่แล้วค่ะ");
            }
            return BF::result(false, $e->getMessage());
        }
    }

}
