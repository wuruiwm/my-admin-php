<?php

namespace App\Http\Controllers\Api;

use App\Models\Ssl;
use Illuminate\Http\Request;

class SslController extends BaseController
{
    //ssl证书内容
    public function detail(Request $request){
        $type = $request->input('type');
        $file = $request->input('file');
        $data = Ssl::getSslData();
        if(empty($data['key']) || empty($data['pem']) || empty($data['end_time'])){
            return self::error("获取ssl证书失败");
        }
        if(!empty($type) && !empty($file) && $type == 'dl' && in_array($file,['key','pem'])){
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$file.'.txt"');
            header('Content-Transfer-Encoding: binary');
            echo $data[$file];
            return;
        }
        return self::success("获取成功",null,null,$data);
    }
}
