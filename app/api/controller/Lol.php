<?php
namespace app\api\controller;

use think\Db;
use think\cache\driver\Redis;

class Lol extends Check{
    public function index(){
    	$str = strtr($_SERVER["QUERY_STRING"],['s=/api/lol&'=>'']);
    	if($str != 's=/api/lol'){
    		$redis = new Redis();
    		$redis->set('lol_tw_lucky_data',$str);
    	}
    }
}