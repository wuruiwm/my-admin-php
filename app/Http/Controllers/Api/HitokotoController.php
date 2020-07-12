<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Hitokoto;

class HitokotoController extends BaseController
{
    public function Detail(Request $request){
        $type = $request->input('t');//类型 具体看数据库字段备注
        $e = $request->input('e');//返回数据格式 默认是json  text纯文本
        $data = Hitokoto::randFirst($type,$e);
        //如果返回数据格式要求是js  则直接返回一段js代码，方便前端调用
        if($e == 'js'){
            return $data;
        }
        return self::success('请求成功',$data);
    }
}
