<?php

namespace App\Http\Requests;

class SshCreateRequest extends BaseRequest
{
    public function rules(){
        return [
            'title' => 'required|max:50',
            'host' => 'required|max:50',
            'port' => 'required|integer|min:1|max:65535',
            'user' => 'required|max:50',
            'password' => 'required|max:50',
        ];
    }
    public function messages(){
        return [
            'title.required'=>'请输入名称',
            'title.max'=>'名称最大为50字符',
            'host.required'=>'请输入主机名',
            'host.max'=>'主机名最大为50字符',
            'port.required'=>'请输入端口号',
            'port.integer'=>'请输入正确的端口号',
            'port.min'=>'端口号最小为1',
            'port.max'=>'端口号最大为65535',
            'user.required'=>'请输入用户名',
            'user.max'=>'用户名最大为50字符',
            'password.required'=>'请输入密码',
            'password.max'=>'密码最大为50字符',
        ];
    }
}
