<?php
namespace app\admin\model;

use \think\Model;
use \think\Db;
class Yiyan extends Model{
	public function list($number = 0,$limit = 15,$where = '',$name = ''){
        $data = Db::name('yiyan')
        ->where('content|from|by','like','%'.$name.'%')
        ->where($where)
        ->order('id asc')
        ->limit($number,$limit)
        ->select();
        return $data;
    }
    public function count($where = '',$name = ''){
        $count = Db::name('yiyan')
        ->where('content|from|by','like','%'.$name.'%')
        ->where($where)
        ->count('id');
        return $count;
    }
}
