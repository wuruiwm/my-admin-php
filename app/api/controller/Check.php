<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2017 https://nikm.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 傍晚升起的太阳 < wuruiwm@qq.com >
// +----------------------------------------------------------------------


namespace app\api\controller;

use think\Controller;
use think\cache\driver\Redis;

class Check extends Controller
{
    protected function _initialize(){
        //api接口限流
        $num = config('api_num');//每多少秒可以请求的次数
        $time = config('api_time');//多少秒内
        $ip = get_client_ip();
        $redis_key = md5($ip);
        $redis = new Redis();
        $count = $redis->get($redis_key);
        if(empty($count)){
            $redis->set($redis_key,1,$time);
        }else if($count >= $num){
            msg(0,'请求频率太高,请重试');
        }else{
            $redis->inc($redis_key);
        }
    }
}
