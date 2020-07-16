<?php
namespace app\api\controller;

class Ssl extends Check{
    public function index(){
    	$data["key"] = file_get_contents(__DIR__."/../../admin/controller/ssl_key.txt");
    	$data["pem"] = file_get_contents(__DIR__."/../../admin/controller/ssl_pem.txt");
    	echo json_encode($data);exit();
    }
}