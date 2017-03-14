<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\link;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class linkController extends Controller
{
    //get.admin/link    友情链接列表
    function index(){
        $data=link::orderBy('link_order','asc')->get();
        return view('admin.link.index',compact('data'));
    }

    function changeOrder(){
        $input=Input::all();
        $link=link::find($input['link_id']);
        $link->link_order=$input['link_order'];
        $re=$link->update();
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

    //get.admin/link/{link}         显示单个友情链接信息
    function show(){

    }

    //get.admin/link/create     添加友情链接
    function create(){
        $data=[];
        return view('admin.link.add',compact('data'));
    }

    //post.admin/category   添加友情链接提交
    function store(){
        $input=Input::except('_token');
        $rules=[
            'link_name'=>'required',
            'link_url'=>'required'
        ];
        $msg=[
            'link_name.required'=>'请填写友情链接名称。',
             'link_url.required'=>'请填写友情链接url。'
        ];
        $validate=Validator::make($input,$rules,$msg);
        if($validate->passes()){
            $re=link::create($input);
            if($re){
                return redirect('admin/link');
            }else{
                return back()->with('errors','数据库填充失败，请稍后再试。');
            }
        }else{
            return back()->withErrors($validate);
        }
    }

    //get.admin/link/{link}/edit         编辑友情链接
    function edit($link_id){
        $field=link::find($link_id);
        return view('admin.link.edit',compact('field'));
    }

    //put.admin/link/{link}    更新友情链接
    function update($link_id){
        $input=Input::except('_token','_method');
        $re=link::where('link_id',$link_id)->update($input);
        if($re){
            return redirect('admin/link');
        }else{
            return back()->with('errors','分类信息跟新失败，请稍后重试！');
        }
    }

    //delete.admin/link/{link}      删除单个友情链接
    function destroy($link_id){
        $re=link::where('link_id',$link_id)->delete();
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
