<?php
namespace app\api\controller;

use think\Db;
use think\cache\driver\Redis;
use app\admin\model\Location;
use app\api\validate\TestValidate;


class Test extends Check{
    public function index(){
    	$data = Location::select();
    	foreach ($data as $k =>$v) {
    		$data[$k]->address = 'ttt';
    	}
    	return success("获取列表成功",$data);
    }
    public function page(){
    	$TestValidate = new TestValidate;
    	$data = input();
    	if(!$TestValidate->check($data)){
    		return error($TestValidate->getError());
    	}else{
    		return success("成功");
    	}
    }
}