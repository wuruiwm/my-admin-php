<?php
namespace app\admin\model;

use \think\Model;
use \think\Db;
class ShortLinks extends Model{
	public function list($number = 0,$limit = 15,$name = ''){
        $data = Db::name('ShortLinks')
        ->order('id asc')
        ->where('tail|link|remark','like','%'.$name.'%')
        ->limit($number,$limit)
        ->select();
        return $data;
    }
    public function count($name = ''){
        $count = Db::name('ShortLinks')
        ->where('tail|link|remark','like','%'.$name.'%')
        ->count('id');
        return $count;
    }
    public function tail_repeat($tail = ''){
        $data = Db::name('ShortLinks')
        ->where('tail',$tail)
        ->find();
        $data ? $bool = false : $bool = true;
        return $bool;
    }
}
