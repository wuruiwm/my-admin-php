<?php

namespace App\Http\Requests;

class SshUpdateRequest extends BaseRequest
{
    public function rules(){
        $SshCreateRequest = new SshCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($SshCreateRequest->rules(),$RequiredIdRequest->rules());
    }
    public function messages(){
        $SshCreateRequest = new SshCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($SshCreateRequest->messages(),$RequiredIdRequest->messages());
    }
}
