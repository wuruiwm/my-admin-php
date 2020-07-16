<?php
namespace app\api\controller;

use think\Db;

class Location extends Check{
    public function index(){
        $rule = [
            'tail' => 'require',
            'address'=>'require',
            'lat'=>'require',
            'lng'=>'require',
        ];
        $msg = [
            'tail.require' => '后缀不能为空',
            'address.require' => '地址不能为空',
            'lat.require' => 'lat不能为空',
            'lng.require' => 'lng不能为空',
        ];
        $post = check($rule,$msg);
        $data = [
            'address'=>$post['address'],
            'lat'=>$post['lat'],
            'lng'=>$post['lng'],
            'ip'=>get_client_ip(),
        ];
        if($post['tail'] == config('location_api_key')){
        	$data['create_time'] = time();
            $data['tail'] = '通用密钥上传';
            $res = Db::name('location')->insert($data);
        }else{
            $res = Db::name('location')->where('tail',$post['tail'])->update($data);
        }
        $res ? msg(1,'位置上传成功') : msg(0,'位置上传失败');
    }
}