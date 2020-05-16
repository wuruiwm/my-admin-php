<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Models\Translate;
use App\Http\Requests\TranslateRequest;

class TranslateController extends BaseController
{
    public function index(){
        $language = Translate::language();
        return View::make('admin.translate.index',['language'=>$language]);
    }
    public function translate(TranslateRequest $request){
        $text = $request->input('text');
        $language = $request->input('language');
        if(empty($string = Translate::getData($text))){
            return self::error("输入的文字中,没有中文,无需翻译");
        }
        $result = Translate::translate($string,'zh',$language);
        if(empty($result['trans_result'])){
            return self::error("翻译过快或出错,请稍后重试");
        }
        $text = Translate::replace($result['trans_result'],$text);
        return self::success("翻译成功",null,null,['text'=>$text]);
    }
}
