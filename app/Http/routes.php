<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['namespace'=>'Home'],function (){
    Route::get('/','IndexController@index');
    Route::get('/cate/{cate_id}','IndexController@cate');
    Route::get('/a/{art_id}','IndexController@article');
});

Route::group(['prefix'=>'admin/','namespace'=>'Admin'],function (){
    Route::any('login', 'loginController@login');
    Route::get('code', 'loginController@code');
});

Route::group(['middleware'=>'admin.login','prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::get('/', 'indexController@index');
    Route::get('info', 'indexController@info');
    Route::get('quit', 'loginController@quit');
    Route::any('resetPSW','indexController@resetPSW');

    Route::post('cate/changeOrder','categoryController@changeOrder');
    Route::resource('category','categoryController');

    Route::resource('article','articleController');

    Route::post('link/changeOrder','linkController@changeOrder');
    Route::resource('link','linkController');

    Route::post('navs/changeOrder','navsController@changeOrder');
    Route::resource('navs','navsController');

    Route::get('config/putFile','ConfigController@putFile');
    Route::post('config/changeContent','ConfigController@changeContent');
    Route::post('config/changeOrder','ConfigController@changeOrder');
    Route::resource('config','ConfigController');

    Route::any('upload','commonController@upload');
});