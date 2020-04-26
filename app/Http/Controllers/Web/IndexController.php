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

use App\Models\Frp;
use Illuminate\Support\Facades\Request;

class IndexController extends BaseController
{
    public function index(Request $request){
        $host = $_SERVER['SERVER_NAME'];
        if(!empty($data = Frp::where('domain_name',$host)->first())){
            if(!empty($data->is_https)){
                $url = "https://$host:" . admin_config('frp_https_port');
            }else{
                $url = "http://$host:" . admin_config('frp_http_port');
            }
            return redirect($url,301);
        }
        return redirect('/admin',302);
    }
}
