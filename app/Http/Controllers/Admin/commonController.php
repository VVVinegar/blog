<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class commonController extends Controller
{
    //图片上传
    public function upload(){
        $file=Input::file('Filedata');
        if($file->isValid()){
            $realPath=$file->getRealPath();
            //上传文件后缀
            $extension=$file->getClientOriginalExtension();
            $newName=date("YmdHis").mt_rand(100,999).'.'.$extension;
            $path=$file->move(base_path().'/public/uploads',$newName);
            echo $path;
        }
    }
}
