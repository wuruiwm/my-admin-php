<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-27 11:08:53
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 09:29:49
 */

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\View;

class PayController extends BaseController
{
    public function index(){
        return View::make('web.pay');
    }
}
