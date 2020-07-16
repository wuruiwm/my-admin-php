<?php
namespace app\admin\controller;

use think\Db;
class Ssl extends Permissions{
    public function index(){
  //      $data = @json_decode(file_get_contents('https://nas_web.nikm.cn:9999/ssl.php'),true);
  //      if(empty($data)){
  //      	exit("与群晖vmm centos7通信失败,请检查家里是否断网,停电");
  //      }
		// $ssl_pem_txt = fopen(__DIR__."/ssl_pem.txt", "w");
		// fwrite($ssl_pem_txt, $data['pem']);
		// fclose($ssl_pem_txt);
		// $ssl_key_txt = fopen(__DIR__."/ssl_key.txt", "w");
		// fwrite($ssl_key_txt, $data['key']);
		// fclose($ssl_key_txt);
		// var_dump(function_exists('exec'));exit();
		exec("openssl x509 -in ".__DIR__."/ssl_pem.txt"." -noout -text",$res);
		foreach ($res as $k =>$v){
			if(strpos($v,'Not After') !== false){
				$v = strtotime(str_replace('Not After : ','',$v));
				$end_time = date('Y-m-d H:i:s',$v);
			}
		}
		$data["key"] = file_get_contents(__DIR__."/ssl_key.txt");
		$data["pem"] = file_get_contents(__DIR__."/ssl_pem.txt");
		$this->assign('end_time',$end_time);
        $this->assign('data',$data);
        return $this->fetch();
    }
}