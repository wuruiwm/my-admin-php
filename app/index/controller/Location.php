<?php
namespace app\index\controller;

use \think\Db;
use \think\Controller;
use \think\Request;

class Location extends Controller
{
    public function index(){
       $tail = str_replace('location/',"",Request::instance()->path());
       if($tail == config('location_api_key')){
           goto Fetch;
       }
       $res = Db::name('location')->where('tail',$tail)->find();
       if(empty($res) || !empty($res['address'])){
           msg(0,'链接不存在,请稍后重试');
       }
       goto Fetch;
       Fetch:
       $this->assign('tail',$tail);
       $this->assign('key',config('tx_map_key'));
       return $this->fetch();
    }
}