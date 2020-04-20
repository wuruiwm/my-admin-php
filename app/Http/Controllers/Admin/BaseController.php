<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
    protected static function success($msg = "请求成功",$data = null,$count = null,$field = null){
        $array = [
            'code' => 0,
            'msg' => $msg,
        ];
        $data === null || $array['data'] = $data;
        $count === null || $array['count'] = $count;
        if($field !== null && is_array($field)){
            $array = array_merge($array,$field);
        }
        return self::json($array);
    }
    protected static function error($msg = "请求失败"){
        $array = [
            'code' => 1,
            'msg' => $msg,
        ];
        return self::json($array);
    }
    protected static function json($array){
        return Response::json($array);
    }
}
