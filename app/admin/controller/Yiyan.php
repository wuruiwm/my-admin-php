<?php
namespace app\admin\controller;

use think\Db;
use think\Validate;
use think\Cache;
class Yiyan extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function list(){
        extract(page());
        $name = input('name');
        $type = input('type');
        $where = '';
        if($type){
            if($type == 'a' || $type == 'b' || $type == 'c' || $type == 'd' || $type == 'e' || $type == 'f' || $type == 'g' ){
                $where = "type='".$type."'"; 
            }
        }
        $data = model('yiyan')->list($number,$limit,$where,$name);
        $data = array_date($data,['create_time','update_time']);
        foreach ($data as $k => $v) {
            $data[$k]['type_name'] = yiyan_type($v['type']);
        }
        $count = model('yiyan')->count($where,$name);
        showjson(['data'=>$data,'code'=>0,'count'=>$count]);
    }
    public function post(){
        $rule = [
            'id'  => 'require|number',
            'content' => 'require',
            'type' => 'in:a,b,c,d,e,f,g',
            'from'=>'require',
            'by'=>'require',
        ];
        $msg = [
            'id.require' => '请传入id',
            'id.number'     => '请传入正确的id',
            'content.require'   => '内容不能为空',
            'type.in'  => '请选择正确的类型',
            'from.require'        => '出处不能为空',
            'by.require' => '提交者不能为空',
        ];
        $post = check($rule,$msg);
        $id = intval($post['id']);
        $data = [
            'content'=>$post['content'],
            'type'=>$post['type'],
            'from'=>$post['from'],
            'by'=>$post['by'],
            'update_time'=>time(),
        ];
        Db::name('yiyan')->where('id',$id)->update($data) ? msg(1,'修改成功') : msg(0,'修改失败');
    }
}