<?php

namespace App\Http\Requests;

class PageRequest extends BaseRequest
{
    public function rules(){
        return [
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
        ];
    }
    public function messages(){
        return [
            'page.required'=>'页码数不能为空',
            'page.integer'=>'请传入正确的页码数',
            'page.min'=>'页码数最小值为1',
            'limit.required'=>'每页条数不能为空',
            'limit.integer'=>'请传入正确的每页条数',
            'limit.min'=>'每页条数最小值为1',
        ];
    }
}
