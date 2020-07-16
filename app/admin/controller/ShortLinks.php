<?php
namespace app\admin\controller;

use think\Db;

class ShortLinks extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function list(){
        extract(page());
        $name = input('name');
        $data = model('ShortLinks')->list($number,$limit,$name);
        $data = array_date($data,['create_time','update_time']);
        $count = model('ShortLinks')->count($name);
        $short_links = config('short_links');
        foreach($data as $k => $v){
            $data[$k]['tail_url'] = $short_links . $v['tail'];
            //修改为前端生成二维码，优化性能https://github.com/diamont1001/jrQrcode
        }
        showjson(['data'=>$data,'code'=>0,'count'=>$count]);
    }
    public function post(){
        $rule = [
            'tail' => 'require',
            'link' => 'require|url',
        ];
        $msg = [
            'tail.require' => '后缀不能为空',
            'link.require' => '跳转地址不能为空',
            'link.url'     => 'url不合法',
        ];
        $post = check($rule,$msg);
        $id = input('id');
        $remark = input('remark');
        $res = Db::name('ShortLinks')->where('link',$post['link'])->find();
        if(!empty($res)){
            msg(0,'检测到重复短链');
        }
        $data = [
            'tail'=>$post['tail'],
            'link'=>$post['link'],
            'remark'=>$remark,
            'update_time'=>time()
        ];
        if (!empty($id) && is_numeric($id)) {
            Db::name('ShortLinks')->where('id',$id)->update($data) ? msg(1,'修改成功') : msg(0,'修改失败');
        }else{
            model('ShortLinks')->tail_repeat($data['tail']) ? $data['create_time'] = time() : msg(0,'该短链已存在,请更换后重试');
            Db::name('ShortLinks')->insert($data) ? msg(1,'新增成功') : msg(0,'新增失败');
        }
    }
    public function del(){
        $id = input_id();
        Db::name('ShortLinks')->where('id',$id)->delete() ? msg(1,'删除成功') : msg(0,'删除失败');
    }
    public function rand(){
        $rand = getRandomChar(4);
        $res = true;
        while($res){
            $res = Db::name('ShortLinks')->where('tail',$rand)->find();
            $rand = getRandomChar(4);
        }
        $rand ? showjson(['status'=>1,'rand'=>$rand]) : showjson(['status'=>0]);
    }
}
