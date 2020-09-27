<?php
namespace App\Models;

class PtDownload extends Base
{
    protected $table = 'pt_download';
    /**
     * 获取待下载种子
     */
    public static function getDownloadList(){
        return self::where('status',0)
            ->select(['download_url','hash'])
            ->get();
    }
}
