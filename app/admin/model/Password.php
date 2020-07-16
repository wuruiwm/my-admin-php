<?php
namespace app\admin\model;

use \think\Model;
use \think\Db;
class Password extends Model{
	public function list($number = 0,$limit = 15,$name = ''){
        $data = Db::name('password')->where('name|user|remark','like','%'.$name.'%')
        ->order('id asc')
        ->limit($number,$limit)
        ->select();
        return $data;
    }
    public function count($name = ''){
        $count = Db::name('password')
        ->where('name|user|remark','like','%'.$name.'%')
        ->count('id');
        return $count;
    }
}
