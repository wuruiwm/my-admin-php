<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator){
        $data = [
            'code'=> 1,
            'msg'=> $validator->errors()->first()
        ];
        throw new HttpResponseException(Response::json($data));
    }
}
