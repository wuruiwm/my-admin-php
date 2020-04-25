<?php

namespace App\Http\Requests;

class FrpEditRequest extends BaseRequest
{
    public function rules(){
        $FrpCreateRequest = new FrpCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($FrpCreateRequest->rules(),$RequiredIdRequest->rules());
    }
    public function messages(){
        $FrpCreateRequest = new FrpCreateRequest();
        $RequiredIdRequest = new RequiredIdRequest();
        return array_merge($FrpCreateRequest->messages(),$RequiredIdRequest->messages());
    }
}
