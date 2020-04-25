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
use App\Models\ShortLinks;

class ShortLinksController extends BaseController
{
    public function index(){
        if(!empty($data = ShortLinks::where('tail',request()->path())->first())){
            return redirect($data->link, 301);
        }
        return View::make('errors.404');
    }
}
