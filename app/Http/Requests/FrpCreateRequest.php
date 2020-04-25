<?php

namespace App\Http\Requests;

class FrpCreateRequest extends BaseRequest
{
    public function rules(){
        return [
            'domain_name' => 'required',
            'is_https' => 'required|in:0,1',
        ];
    }
    public function messages(){
        return [
            'domain_name.required'=>'请输入域名',
            'is_https.required'=>'请选择是否HTTPS',
            'is_https.in'=>'请传入正确的is_https',
        ];
    }
}
