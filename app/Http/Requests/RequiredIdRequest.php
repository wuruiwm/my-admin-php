<?php

namespace App\Http\Requests;

class RequiredIdRequest extends BaseRequest
{
    public function rules(){
        return [
            'id' => 'required|integer|min:1',
        ];
    }
    public function messages(){
        return [
            'page.required'=>'id不能为空',
            'page.integer'=>'请传入正确的id',
            'page.min'=>'id最小值为1',
        ];
    }
}
