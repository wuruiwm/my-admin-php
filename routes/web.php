<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//短链
Route::fallback('Web\ShortLinksController@index');

Route::group(['namespace'=>'Web'],function (){
    Route::match(['get','post'],'/','IndexController@index');
    //三合一收款码
    Route::get('/pay','PayController@index');
    //microsoft office365 自助注册 提交接口
    Route::post('/microsoft/submit','MicrosoftController@submit')->name('microsoft.submit');
});
