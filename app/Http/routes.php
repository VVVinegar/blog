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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::any('login', 'loginController@login');
    Route::get('code', 'loginController@code');
});

Route::group(['middleware'=>'admin.login','prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::get('index', 'indexController@index');
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

    Route::any('upload','commonController@upload');
});