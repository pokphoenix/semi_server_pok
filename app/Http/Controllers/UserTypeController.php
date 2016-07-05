<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\Request;
use Validator;
use Input;
use BF;

class UserTypeController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $input = BF::decodeInput($request->getContent());
        $rules = array(
            'name' => 'required|min:3|max:50',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        }
        try {
            unset($input['token']);
            $status = UserType::create($input);
            if($status === NULL) {
                return BF::result(false, 'failed!');
            }
        } catch ( \Illuminate\Database\QueryException $e) {
            if($e->getCode() == 23000) {
                return BF::result(false, "ชื่อซ้ำ: {$input['name']}");
            }
            return BF::result(false, $e->getMessage());
        }
        $data = [] ;
        return BF::result(true, $data, 'usertype create');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $data =  UserType::find($id);
        return BF::result(true, ['action' => 'edit', 'data' => $data ]);
    }

    public function update($id)
    {
        if(empty($id)) {
            return BF::result(false, 'ไม่พบข้อมูลนี้ค่ะ');
        }
        $data = Input::all();
        $data = array_diff_key($data, array_flip(['id','_method','deleted_at','deleted_by','updated_at','created_at']));
        //$data["updated_by"] = Session::get('user_id');
        try {
            $status = UserType::whereId($id)->update($data);
            if($status == 1) {
                return BF::result(true, ['action' => 'update', 'id' => $id]);
            }
        } catch ( \Illuminate\Database\QueryException $e) {
            if($e->getCode() == 23000) {
                return BF::result(false, "ชื่อซ้ำ: {$data['name']}");
            }
            return BF::result(false, $e->getMessage());
        }
        return BF::result(false, 'failed!');
    }

    public function destroy($id)
    {
        if(empty($id)){
            return BF::result(false, 'ไม่พบข้อมูลนี้ค่ะ');
        }
        $data = UserType::find($id);
        if (is_null($data)) {
            UserType::withTrashed()->whereId($id)->first()->restore();
            return BF::result(true, ['action' => 'restore', 'id' => $id]);
        }else{
            $data->delete();
            return BF::result(true, ['action' => 'delete', 'id' => $id]);
        }
    }
}
