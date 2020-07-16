<?php
namespace app\admin\model;

use \think\Model;
use \think\Db;
class Jump extends Model{
	public function list($number = 0,$limit = 15,$where = '',$name = ''){
        $data = Db::name('jump')
        ->order('id asc')
        ->where($where)
        ->where('domain_name|remark','like','%'.$name.'%')
        ->limit($number,$limit)
        ->select();
        return $data;
    }
    public function count($where = '',$name = ''){
        $count = Db::name('jump')
        ->where($where)
        ->where('domain_name|remark','like','%'.$name.'%')
        ->count('id');
        return $count;
    }
}
