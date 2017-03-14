<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class navsController extends Controller
{
    //get.admin/navs    自定义导航列表
    function index(){
        $data=navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }

    function changeOrder(){
        $input=Input::all();
        $navs=navs::find($input['nav_id']);
        $navs->nav_order=$input['nav_order'];
        $re=$navs->update();
        if($re){
            $data=[
                'status'=>1,
                'msg'=>'排序更改成功！'
            ];
        }else{
            $data=[
                'status'=>0,
                'msg'=>'排序更改失败！'
            ];
        }
        return $data;
    }

    //get.admin/navs/{navs}         显示单个自定义导航信息
    function show(){

    }

    //get.admin/navs/create     添加自定义导航
    function create(){
        $data=[];
        return view('admin.navs.add',compact('data'));
    }

    //post.admin/navs   添加自定义导航提交
    function store(){
        $input=Input::except('_token');
        $rules=[
            'nav_name'=>'required',
            'nav_url'=>'required'
        ];
        $msg=[
            'nav_name.required'=>'请填写自定义导航名称。',
            'nav_url.required'=>'请填写自定义导航url。'
        ];
        $validate=Validator::make($input,$rules,$msg);
        if($validate->passes()){
            $re=navs::create($input);
            if($re){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','数据库填充失败，请稍后再试。');
            }
        }else{
            return back()->withErrors($validate);
        }
    }

    //get.admin/navs/{navs}/edit         编辑自定义导航
    function edit($nav_id){
        $field=navs::find($nav_id);
        return view('admin.navs.edit',compact('field'));
    }

    //put.admin/navs/{navs}    更新自定义导航
    function update($nav_id){
        $input=Input::except('_token','_method');
        $re=navs::where('nav_id',$nav_id)->update($input);
        if($re){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','分类信息跟新失败，请稍后重试！');
        }
    }

    //delete.admin/navs/{navs}      删除单个自定义导航
    function destroy($nav_id){
        $re=navs::where('nav_id',$nav_id)->delete();
        if($re){
            $data=[
                'state'=>1,
                'msg'=>'删除成功！'
            ];
        }else{
            $data=[
                'state'=>0,
                'msg'=>'操作失败，请稍后重试！'
            ];
        }
        return $data;
    }
}
