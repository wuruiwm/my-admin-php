<?php
namespace app\admin\controller;

use think\Db;
class Jump extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function list(){
        extract(page());
        $name = input('name');
        $is_https = input('is_https');
        $where = '';
        if($is_https !== '' && $is_https == '0'){
            $where .= 'is_https='.$is_https;
        }else if($is_https === '1'){
            $where .= 'is_https='.$is_https;
        }
        $data = model('jump')->list($number,$limit,$where,$name);
        $data = array_date($data,['create_time','update_time']);
        $count = model('jump')->count($where,$name);
        showjson(['data'=>$data,'code'=>0,'count'=>$count]);
    }
    public function https(){
        $id = input_id();
        $https = input('https');
        $res = false;
        if($https == 'true'){
            $res = Db::name('jump')->where('id',$id)->update(['is_https'=>1,'update_time'=>time()]);
        }else if($https == 'false'){
            $res = Db::name('jump')->where('id',$id)->update(['is_https'=>0,'update_time'=>time()]);
        }
        $res ? msg(1,'修改成功') : msg(0,'修改失败');
    }
    public function del(){
        $id = input_id();
        Db::name('jump')->where('id',$id)->delete() ? msg(1,'删除成功') : msg(0,'删除失败');
    }
    public function post(){
        $rule = [
            'domain_name'=>'require',
            'is_https' => 'require|in:0,1',
        ];
        $msg = [
            'domain_name.require'=>'请填写域名',
            'is_https.require'=>'请选择是否是https',
            'domain_name.require'=>'请选择正确的选项',
        ];
        $post = check($rule,$msg);
        $id = input('id');
        $remark = input('remark');
        $data = [
            'domain_name'=>$post['domain_name'],
            'is_https'=>$post['is_https'],
            'remark'=>$remark,
            'update_time'=>time(),
        ];
        if (!empty($id) && is_numeric($id)) {
            Db::name('jump')->where('id',$id)->update($data) ? msg(1,'修改成功') : msg(0,'修改失败');
        }else{
            $data['create_time'] = time();
            Db::name('jump')->insert($data) ? msg(1,'新增成功') : msg(0,'新增失败');
        }
    }
}