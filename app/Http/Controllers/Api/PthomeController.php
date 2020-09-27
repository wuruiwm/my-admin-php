<?php

namespace App\Http\Controllers\Api;

use App\Models\PtDownload;

class PthomeController extends BaseController
{
    //pthome待下载种子列表
    public function list(){
        $list = PtDownload::getDownloadList();
        return self::success('请求成功',$list);
    }
}
