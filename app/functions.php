<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-25 16:17:43
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 11:31:37
 */

//读取后台配置
function adminConfig($key){
    $data = \Illuminate\Support\Facades\Cache::rememberForever('adminConfig', function(){
        return \App\Models\Configuration::pluck('val','key');
    });
    if(isset($data[$key]) && !empty($data[$key])){
        return $data[$key];
    }else{
        return '';
    }
}
//获取用户ip地址
function get_client_ip(){
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    if (getenv('HTTP_X_REAL_IP')) {
        $ip = getenv('HTTP_X_REAL_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip = $ips[0];
    } elseif (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = '0.0.0.0';
    }
    return $ip;
}
//获取当前请求带协议头的域名  例如https://www.baidu.com
function domain_name(){
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}
//随机字符串
function get_rand_string($length = 32){
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}
