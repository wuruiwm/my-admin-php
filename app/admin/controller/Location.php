<?php
namespace app\admin\controller;

use think\Db;

class Location extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function list(){
        extract(page());
        $name = input('name');
        $data = model('location')->list($number,$limit,$name);
        $data = array_date($data,['create_time','update_time']);
        foreach($data as $k => $v){
            $data[$k]['tail_url'] = config('location'). 'location/' . $v['tail'];
        }
        $count = model('location')->count($name);
        showjson(['data'=>$data,'code'=>0,'count'=>$count]);
    }
    public function del(){
        $id = input_id();
        Db::name('location')->where('id',$id)->delete() ? msg(1,'删除成功') : msg(0,'删除失败');
    }
    public function post(){
        $tail = input('tail');
        if(empty($tail)){
            msg(0,'后缀不能为空');
        }
        Db::name('location')->where('tail',$tail)->find() ? msg(0,'后缀已存在,请重新填写') : $data = ['tail'=>$tail,'create_time'=>time(),'update_time'=>time()];
        Db::name('location')->insert($data) ? msg(1,'添加成功') : msg(0,'添加失败');
    }
    public function rand(){
        $rand = getRandomChar(4);
        $res = true;
        while($res){
            $res = Db::name('location')->where('tail',$rand)->find();
            $rand = getRandomChar(4);
        }
        $rand ? showjson(['status'=>1,'rand'=>$rand]) : showjson(['status'=>0]);
    }
}