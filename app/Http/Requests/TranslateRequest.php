<?php

namespace App\Http\Requests;

use App\Models\Translate;

class TranslateRequest extends BaseRequest
{
    public function rules(){
        return [
            'text' => 'required',
            'language' => 'required|in:'.Translate::languageValidationInSting(),
        ];
    }
    public function messages(){
        return [
            'title.required'=>'请输入要翻译的文本',
            'language.required'=>'请选择语言',
            'language.in'=>'请选择正确的语言',
        ];
    }
}
