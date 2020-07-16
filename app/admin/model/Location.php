<?php
namespace app\admin\model;

use \think\Model;
use \think\Db;
class Location extends Model{
	public function list($number = 0,$limit = 15,$name = ''){
        $data = Db::name('location')
        ->order('id asc')
        ->where('tail|address','like','%'.$name.'%')
        ->limit($number,$limit)
        ->select();
        return $data;
    }
    public function count($name = ''){
        $count = Db::name('location')
        ->where('tail|address','like','%'.$name.'%')
        ->count('id');
        return $count;
    }
}
