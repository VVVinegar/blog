<?php

namespace App\Http\Controllers\Admin;


use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class indexController extends commonController
{
    public function index(){
        return view("admin.index");
    }
    public function info(){
        return view('admin.info');
    }
    public function resetPSW(){
        if($input=Input::all()){
            $rules=[
                'password'=>'required|between:6,12|confirmed'
            ];
            $message=[
                'password.required'=>"密码不能为空",
                'password.between'=>"密码必须为6-12位",
                'password.confirmed'=>"两次密码输入必须一致"
            ];
            $validator=Validator::make($input,$rules,$message);
            if($validator->passes()){
                $user=User::first();
                $_password=Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    $user->user_pass=Crypt::encrypt($input['password']);
                    $user->update();
//                    return redirect('admin/info');
                    return back()->with('errors','修改密码成功！');
                }else{
                    return back()->with('errors','原密码错误！');
                }
            }else{
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.resetPSW');
        }
    }
}
