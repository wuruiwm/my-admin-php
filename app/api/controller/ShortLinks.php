<?php
namespace app\api\controller;

use think\Db;
use think\Validate;

class ShortLinks extends Check{
    public function create(){
    	echo '接口已关闭';exit();
        $get = input('get.');
        $rule = [
            'url' => 'require|url',
        ];
        $msg = [
            'url.require' => '跳转地址不能为空',
            'url.url'     => 'url不合法',
        ];
        $validate = new Validate($rule,$msg);
        if (!$validate->check($get)) {
            msg(0,$validate->getError());
        }
        $url = $get['url'];
        $res = Db::name('ShortLinks')->where('link',$url)->find();
        if(!empty($res)){
            showjson(['status'=>1,'url'=>config('short_links').$res['tail'],'msg'=>'检测到重复短链']);
        }
        $rand = getRandomChar(4);
        $res = true;
        while($res){
            $res = Db::name('ShortLinks')->where('tail',$rand)->find();
            $rand = getRandomChar(4);
        }
        $data = [
            'tail'=>$rand,
            'link'=>$url,
            'remark'=>'',
            'update_time'=>time()
        ];
        model('admin/ShortLinks')->tail_repeat($data['tail']) ? $data['create_time'] = time() : msg(0,'该短链已存在,请更换后重试');
        Db::name('ShortLinks')->insert($data) ? showjson(['status'=>1,'url'=>config('short_links').$rand,'msg'=>'创建短链成功']) : msg(0,'创建失败');
    }
}