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
            $cache_key = 'pem:'.md5($data['pem']);
            $res = Cache::get($cache_key);
            if(empty($res)){
                $pem_file_path = public_path().'/../storage/app/public/ssl_pem.txt';
                file_put_contents($pem_file_path,$data['pem']);
                exec("openssl x509 -in ".$pem_file_path." -noout -text",$res);
                unlink($pem_file_path);
                Cache::put($cache_key,$res,60*60*24);
            }
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
