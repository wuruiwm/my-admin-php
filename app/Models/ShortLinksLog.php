<?php
namespace App\Models;

class ShortLinksLog extends Base
{
    protected $table = 'short_links_log';
    public static function ipToPosition($ip){
        $ip = '60.168.68.160';
        $url = "https://apis.map.qq.com/ws/location/v1/ip?ip=$ip&key=".admin_config('short_links_tx_map_key');
        $res = file_get_contents($url);//返回的是json
        $data = json_decode($res,true);//转成数组
        if(!empty($data) && $data['status'] == 0 && !empty($data['result']) && !empty($data['result']['ad_info'])){
            return $data['result']['ad_info']['nation'] . $data['result']['ad_info']['province'] . $data['result']['ad_info']['city'] . $data['result']['ad_info']['district'];
        }
        return '';
    }
}
