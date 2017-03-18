<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\article;
use App\Http\Model\Category;
use App\Http\Model\link;
use App\Http\Model\navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends commonController
{
    function index(){
        //点击量最高的6篇文章
        $hot=article::orderBy('art_view','desc')->take(6)->get();
        //图文列表 带分页
        $data=article::orderBy('art_time','desc')->paginate(5);

        //友情链接
        $links=link::orderBy('link_order','desc')->get();
        return view('home.index',compact('hot','data','new','links'));
    }

    function cate($cate_id){
        $field=Category::find($cate_id);
        //图文列表 带分页
        $data=article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(5);
        //当前分类子分类
        $submenu=Category::where('cate_pid',$cate_id)->get();
        //浏览量字段自增
        Category::where('cate_id',$cate_id)->increment('cate_view',1);
        return view('home.list',compact('field','data','submenu'));
    }

    function article($art_id){
        $field=article::Join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();
        //获取上一篇下一篇信息
        $article['pre']=article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
        $article['next']=article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();
        //获取相关文章信息
        $data=article::where('cate_id',$field->cate_id)->orderBy('art_id','desc')->take(4)->get();
        //浏览量字段自增
        article::where('art_id',$art_id)->increment('art_view',1);
        return view('home.new',compact('field','article','data'));
    }
}
