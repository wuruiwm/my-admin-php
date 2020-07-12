<?php

namespace App\Http\Controllers\Api;

use App\Models\Ssl;

class SslController extends BaseController
{
    public function detail(){
        $data = Ssl::getSslData();
        return self::success("获取成功",null,null,$data);
    }
}
