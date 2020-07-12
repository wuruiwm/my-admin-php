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
use App\Models\ShortLinksLog;

class ShortLinksController extends BaseController
{
    //短链跳转 如果没有短链，则返回404
    public function index(){
        if(!empty($ShortLinks = ShortLinks::where('tail',request()->path())->select('id','tail','link')->first())){
            $ip = get_client_ip();
            $data = [
                'short_links_id'=>$ShortLinks->id,
                'tail'=>$ShortLinks->tail,
                'link'=>$ShortLinks->link,
                'ip'=>$ip,
                'position'=>ShortLinksLog::ipToPosition($ip),
            ];
            try {
                ShortLinksLog::create($data);
                return redirect($ShortLinks->link, 301);
            } catch (\Throwable $th) {
                return View::make('errors.404');
            }
        }
        return View::make('errors.404');
    }
}
