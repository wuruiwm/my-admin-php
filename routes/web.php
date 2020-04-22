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

Route::get('/', function () {
    return redirect('/admin', 301);
});

//短链
Route::fallback(function (){
    if(empty($data = \App\Models\ShortLinks::where('tail',request()->path())->first())){
       return \Illuminate\Support\Facades\View::make('errors.404');
    }
    return redirect($data->link, 301);
});
