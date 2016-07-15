<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;
use Input;
use BF;

class UserTypeController extends Controller
{
    public function index()
    {
        $perm = Session::get('perm');
        $cols = [
            'id',
            'name',
            'deleted_at'
        ] ;
        $data = [];
//        if (BF::getPermission('usertype.edit')) {
//            $sql = UserType::withTrashed()->select($cols);
//        } else {
//            $sql = UserType::select($cols);
//        }

        $sql = UserType::select($cols);

        // -- Order
        foreach (json_decode(Input::get('order')) as $order) {
            $sql->orderBy($order->column, $order->dir);
        }

        // -- Filter
        foreach (json_decode(Input::get('columns')) as $col) {
            $column = $col->data;
            $val = $col->search;
            if (in_array($column, $cols) && ( $val != '') ) {
                $sql->where($column, 'LIKE', '%' . $val . '%');
            }
        }

//        DB::enableQueryLog();
//        $sql->get();
//        $query = DB::getQueryLog();
//        return $query ;
//        exit();
        try {
            $count = $sql->count();
            $data = $sql->skip(Input::get('start'))->take(Input::get('length'))->get();
            $result = BF::dataTable($data, $count, $count, $perm) ;
        } catch ( \Illuminate\Database\QueryException $e) {
            return BF::result(false, $e->getMessage());
        }

        return BF::result(true, $result, '[usertype] index');

    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $decode = BF::decodeInput($request->getContent());
        $input = (array)$decode['data'] ;
        $rules = array(
            'name' => 'required|min:3|max:50',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        }

        if(BF::TestSetting('userTypeAlert')){
            //--- สำหรับเทส alert popup
            return BF::result(false, "ชื่อซ้ำ: {$input['name']}");
        }

        if(BF::TestSetting('userTypeNotSave')){
            //--- สำหรับเทส ไม่ต้องคอยลบข้อมูลเวลาทดสอบฝั่ง client
        }else{
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
        }
        $data = [] ;
        return BF::result(true, $data, '[usertype] create');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $data =  UserType::find($id);
        return BF::result(true, $data, '[usertype] edit');
    }

    public function update(Request $request,$id)
    {
        if(empty($id)) {
            return BF::result(false, 'ไม่พบข้อมูลนี้ค่ะ');
        }
        $decode = BF::decodeInput($request->getContent());
        $input = (array)$decode['data'] ;
        $input = array_diff_key($input, array_flip(['id','_method','deleted_at','deleted_by','updated_at','created_at']));
        $rules = array(
            'name' => 'required|min:3|max:50',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return BF::result(false, $validator->messages()->first());
        }
        //$data["updated_by"] = Session::get('user_id');
        try {
            $status = UserType::whereId($id)->update($input);
            if($status == 1) {
                $data = [
                    'id' => $id
                ] ;
                return BF::result(true, $data, '[usertype] update');
            }
        } catch ( \Illuminate\Database\QueryException $e) {
            if($e->getCode() == 23000) {
                return BF::result(false, "ชื่อซ้ำ: {$input['name']}");
            }
            return BF::result(false, $e->getMessage());
        }
        return BF::result(false, 'failed!');
    }

    public function destroy($id)
    {
        if(empty($id)) {
            return BF::result(false, 'ไม่พบข้อมูลนี้ค่ะ');
        }
        $data = UserType::find($id);
        if(BF::TestSetting('userTypeNotDelete')){
            //--- สำหรับเทส alert popup
            return BF::result(true, $data, '[usertype] delete test');
        }

        if (is_null($data)) {
            UserType::withTrashed()->whereId($id)->first()->restore();
            return BF::result(true, $data, '[usertype] restore');
        }else{
            $data->delete();
            return BF::result(true, $data, '[usertype] delete');
        }
    }
}
