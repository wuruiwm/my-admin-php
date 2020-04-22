<?php

namespace App\Http\Requests;

class ShortLinksCreateRequest extends BaseRequest
{
    public function rules(){
        return [
            'tail' => 'required|max:255|alpha_num',
            'link' => 'required|max:255|active_url',
        ];
    }
    public function messages(){
        return [
            'tail.required'=>'请输入短链',
            'tail.max'=>'短链最大为255字符',
            'tail.alpha_num'=>'短链必须为字母或者数字',
            'link.required'=>'请输入跳转地址',
            'link.max'=>'跳转地址最大为255字符',
            'link.active_url'=>'跳转地址不正确',
        ];
    }
}
