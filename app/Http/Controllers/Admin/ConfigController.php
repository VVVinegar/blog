<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Model\config;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends commonController
{
    //get.admin/config    配置列表
    function index(){
        $data=config::orderBy('conf_order','asc')->get();
        foreach ($data as $k=>$v){
            switch ($v->conf_type){
                case 'input':
                    $data[$k]->_html='<input type="text" name="conf_content[]" class="lg" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html='<textarea type="text" name="conf_content[]" class="lg">'.$v->conf_content.'</textarea>';
                    break;
                case 'radio':
                    $arr=explode('，',$v->conf_value);
                    $str='';
                    foreach ($arr as $m=>$n){
                        $r=explode('|',$n);
                        $c=$v->conf_content==$r[0]? ' checked ': '';
                        $str.='<input type="radio" name="conf_content[]" value="'.$r[0].'" '.$c.' >'.$r[1];
                    }
                    $data[$k]->_html=$str;
                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }

    function changeContent(){
        $input=Input::except('_token');
        foreach ($input['conf_id'] as $k=>$v){
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors','更新成功！');
    }

    function putFile(){
        $config=Config::pluck('conf_content','conf_name')->all();
        $path=base_path().'/config/web.php';
        $str="<?php return ".var_export($config,true).";";
        file_put_contents($path,$str);
    }

    function changeOrder(){
        $input=Input::all();
        $config=config::find($input['conf_id']);
        $config->conf_order=$input['conf_order'];
        $re=$config->update();
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

    //get.admin/config/{config}         显示单个配置信息
    function show(){

    }

    //get.admin/config/create     添加配置
    function create(){
        $data=[];
        return view('admin.config.add',compact('data'));
    }

    //post.admin/config   添加配置提交
    function store(){
        $input=Input::except('_token');
        $rules=[
            'conf_name'=>'required',
            'conf_title'=>'required'
        ];
        $msg=[
            'conf_name.required'=>'请填写配置名称。',
            'conf_title.required'=>'请填写配置标题。'
        ];
        $validate=Validator::make($input,$rules,$msg);
        if($validate->passes()){
            $re=config::create($input);
            if($re){
                $this->putFile();
                return redirect('admin/config');
            }else{
                return back()->with('errors','数据库填充失败，请稍后再试。');
            }
        }else{
            return back()->withErrors($validate);
        }
    }

    //get.admin/config/{config}/edit         编辑配置
    function edit($conf_id){
        $field=config::find($conf_id);
        return view('admin.config.edit',compact('field'));
    }

    //put.admin/config/{config}    更新配置
    function update($conf_id){
        $input=Input::except('_token','_method');
        $re=config::where('conf_id',$conf_id)->update($input);
        if($re){
            return redirect('admin/config');
        }else{
            return back()->with('errors','分类信息跟新失败，请稍后重试！');
        }
    }

    //delete.admin/config/{config}      删除单个配置
    function destroy($conf_id){
        $re=config::where('conf_id',$conf_id)->delete();
        if($re){
            $this->putFile();
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
