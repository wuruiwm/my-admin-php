<?php

namespace App\Http\Requests;

class PasswordCreateRequest extends BaseRequest
{
    public function rules(){
        return [
            'title' => 'required|max:50',
            'user' => 'required|max:50',
            'password' => 'required|max:50',
        ];
    }
    public function messages(){
        return [
            'title.required'=>'请输入名称',
            'title.max'=>'名称最大为50字符',
            'user.required'=>'请输入用户名',
            'user.max'=>'用户名最大为50字符',
            'password.required'=>'请输入密码',
            'password.max'=>'密码最大为50字符',
        ];
    }
}
