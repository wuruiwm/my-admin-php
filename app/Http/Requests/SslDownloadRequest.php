<?php

namespace App\Http\Requests;

class SslDownloadRequest extends BaseRequest
{
    public function rules(){
        return [
            'type' => 'required|in:key,pem',
        ];
    }
    public function messages(){
        return [
            'type.required'=>'请传入类型',
            'type.in'=>'请传入正确的类型',
        ];
    }
}
