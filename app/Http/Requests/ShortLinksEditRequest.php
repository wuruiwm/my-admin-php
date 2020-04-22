<?php

namespace App\Http\Requests;

class ShortLinksEditRequest extends BaseRequest
{
    public function rules(){
        $ShortLinksCreateRequest = new ShortLinksCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($ShortLinksCreateRequest->rules(),$RequiredIdRequest->rules());
    }
    public function messages(){
        $ShortLinksCreateRequest = new ShortLinksCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($ShortLinksCreateRequest->messages(),$RequiredIdRequest->messages());
    }
}
