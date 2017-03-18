<?php

namespace App\Http\Controllers\Home;


use App\Http\Model\article;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Model\navs;
use Illuminate\Support\Facades\View;

class commonController extends Controller
{

    public function __construct(){
        // 最新发布的八篇文章
        $new=article::orderBy('art_time','desc')->take(8)->get();
        $navs=navs::all();
        View::share('navs',$navs);
        View::share('new',$new);
    }
}
