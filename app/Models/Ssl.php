<?php
namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Ssl extends Base
{
    public static function getSslData(){
        $data['key'] = Cache::get("ssl_key");
        $data['pem'] = Cache::get("ssl_pem");
        $data['end_time'] = '';
        if(function_exists('exec') && !empty($data['pem'])){
            file_put_contents(__DIR__.'/ssl_pem.txt',$data['pem']);
            exec("openssl x509 -in ".__DIR__."/ssl_pem.txt"." -noout -text",$res);
            unlink(__DIR__.'/ssl_pem.txt');
            foreach ($res as $k =>$v){
                if(strpos($v,'Not After') !== false){
                    $tmp = strtotime(str_replace('Not After : ','',$v));
                    $data['end_time'] = date('Y-m-d H:i:s',$tmp);
                }
            }
        }
        return $data;
    }
}
