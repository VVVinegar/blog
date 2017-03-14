<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class categoryController extends commonController
{
    //get.admin/category    全部分类列表
    function index(){
        $data=(new Category())->tree();
        return view('admin.category.index')->with('data',$data);
    }
    function changeOrder(){
        $input=Input::all();
        $cate=Category::find($input['cate_id']);
        $cate->cate_order=$input['cate_order'];
        $re=$cate->update();
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

    //get.admin/category/create     添加分类
    function create(){
        $data=Category::where('cate_pid',0)->get();
        return view('admin.category.add',compact('data'));
    }

    //post.admin/category   添加分类提交
    function store(){
        $input=Input::except('_token');
        $rules=[
            'cate_name'=>'required'
        ];
        $msg=[
            'cate_name.required'=>'请填写分类名称。'
        ];
        $validate=Validator::make($input,$rules,$msg);
        if($validate->passes()){
            $re=Category::create($input);
            if($re){
                return redirect('admin/category');
            }else{
                return back()->with('errors','数据库填充失败，请稍后再试。');
            }
        }else{
            return back()->withErrors($validate);
        }
    }

    //get.admin/category/{category}/edit         编辑分类
    function edit($cate_id){
        $field=Category::find($cate_id);
        $data=(new Category())->tree();
        return view('admin.category.edit',compact('field','data'));
    }

    //put.admin/category/{category}    更新分类
    function update($cate_id){
        $input=Input::except('_token','_method');
        $re=Category::where('cate_id',$cate_id)->update($input);
        if($re){
            return redirect('admin/category');
        }else{
            return back()->with('errors','分类信息跟新失败，请稍后重试！');
        }
    }

    //get.admin/category/{category}         显示单个分类信息
    function show(){

    }

    //delete.admin/category/{category}      删除单个分类
    function destroy($cate_id){
        $re=Category::where('cate_id',$cate_id)->delete();
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
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
