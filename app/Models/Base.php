<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

class Base extends Model
{
    protected $guarded = ['id'];
    protected static function success($msg = "请求成功",$data = null,$count = null,$field = null){
        return Controller::success($msg,$data,$count,$field);
    }
    protected static function error($msg = "请求失败"){
        return Controller::error($msg);
    }
    protected static function json($array){
        return Controller::json($array);
    }
}
