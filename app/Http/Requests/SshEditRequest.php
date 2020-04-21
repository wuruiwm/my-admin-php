<?php

namespace App\Http\Requests;

class SshEditRequest extends BaseRequest
{
    public function rules(){
        $PasswordCreateRequest = new SshCreateRequest();
        $rules = $PasswordCreateRequest->rules();
        $rules['id'] = 'required|integer|min:1';
        return $rules;
    }
    public function messages(){
        $PasswordCreateRequest = new SshCreateRequest();
        $messages = $PasswordCreateRequest->messages();
        $messages['page.required'] = 'id不能为空';
        $messages['page.integer'] = '请传入正确的id';
        $messages['page.min'] = 'id最小值为1';
        return $messages;
    }
}
