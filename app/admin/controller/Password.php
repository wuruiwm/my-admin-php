<?php
namespace app\admin\controller;

use think\Db;
class Password extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function list(){
        extract(page());
        $name = input('name');
        $data = model('password')->list($number,$limit,$name);
        $data = array_date($data,['create_time','update_time']);
        foreach ($data as $k =>$v){
        	$v['user'] = decrypt($v['user']);
        	$v['name'] = decrypt($v['name']);
        	$v['password'] = decrypt($v['password']);
        	$v['remark'] = decrypt($v['remark']);
        	$data[$k] = $v;
        }
        $count = model('password')->count($name);
        showjson(['data'=>$data,'code'=>0,'count'=>$count]);
    }
    public function del(){
        $id = input_id();
        Db::name('password')->where('id',$id)->delete() ? msg(1,'删除成功') : msg(0,'删除失败');
    }
    public function post(){
        $name = input('name');
        $password = input('password');
        $id = input('id');
        if (empty($name)) {
            msg(0,'名称不能为空');
        }
        if (empty($password)) {
            msg(0,'密码不能为空');
        }
        $data = [
            'user'=>encrypt($_POST['user']),
            'name'=>encrypt($_POST['name']),
            'password'=>encrypt($_POST['password']),
            'remark'=>encrypt($_POST['remark']),
            'update_time'=>time()
        ];
        if (!empty($id) && is_numeric($id)) {
            Db::name('password')->where('id',$id)->update($data) ? msg(1,'修改成功') : msg(0,'修改失败');
        }else{
            $data['create_time'] = time();
            Db::name('password')->insert($data) ? msg(1,'新增成功') : msg(0,'新增失败');
        }
    }
}