<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\article;
use App\Http\Model\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class articleController extends commonController
{
    //get.admin/article    全部文章列表
    function index(){
        $data=article::orderBy('art_id','desc')->paginate(2);
        return view('admin.article.index',compact('data'));
    }

    //get.admin/article/create     添加文章
    function create(){
        $data=(new Category())->tree();
        return view('admin.article.add',compact('data'));
    }

    //post.admin/article   添加文章提交
    function store(){
        $input=Input::except("_token");
        $input['art_time']=time();

        $rules=[
          'art_title'=>'required',
          'art_content'=>'required'
        ];
        $msg=[
            'art_title.required'=>'标题不能为空',
            'art_content.required'=>'内容不能为空'
        ];
        $validate=Validator::make($input,$rules,$msg);
        if($validate->passes()){
            $re=article::create($input);
            if($re){
                return redirect('admin/article');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validate);
        }
    }

    //get.admin/article/{article}/edit         编辑文章
    function edit($art_id){
        $data=(new Category())->tree();
        $field=article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }

    //put.admin/article/{article}    更新文章
    function update($art_id){
        $input=Input::except('_token',"_method");
        $re=article::where('art_id',$art_id)->update($input);
        if($re){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章更新失败，请稍后重试！');
        }
    }


    //delete.admin/article/{article}      删除单个文章
    function destroy($art_id){
        $re=article::where('art_id',$art_id)->delete();
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
