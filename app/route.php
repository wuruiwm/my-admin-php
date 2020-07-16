<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2017 https://nikm.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 傍晚升起的太阳 < wuruiwm@qq.com >
// +----------------------------------------------------------------------

use think\Route;
//Route::rule('重写之后的url','重写之前的url')
//Route::rule('/admin/test','admin/test/index')

Route::rule('location','index/location/index');
Route::rule('api/location','api/location/index');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];


