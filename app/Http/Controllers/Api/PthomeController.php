<?php

namespace App\Http\Controllers\Api;

use App\Models\PtDownload;
use Illuminate\Http\Request;

class PthomeController extends BaseController
{
    /**
     * pthome待下载种子列表
     */
    public function list(){
        $list = PtDownload::getDownloadList();
        return self::success('请求成功',$list);
    }
    /**
     * pthome下载完，更新状态
     */
    public function downloadUpdate(Request $request){
        $pthome_id = $request->input('pthome_id');
        if(empty($hash)){
            return self::error('pthome_id值不能为空');
        }
        try {
            PtDownload::where('pthome_id',$pthome_id)
                ->update(['status'=>1]);
            return self::success('更新状态成功');
        } catch (\Throwable $th) {
            return self::error('更新状态失败');
        }
    }
}
