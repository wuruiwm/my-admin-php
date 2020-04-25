<?php

namespace App\Http\Requests;

class PasswordUpdateRequest extends BaseRequest
{
    public function rules(){
        $PasswordCreateRequest = new PasswordCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($PasswordCreateRequest->rules(),$RequiredIdRequest->rules());
    }
    public function messages(){
        $PasswordCreateRequest = new PasswordCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($PasswordCreateRequest->rules(),$RequiredIdRequest->rules());
    }
}
