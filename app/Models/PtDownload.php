<?php
namespace App\Models;

class PtDownload extends Base
{
    protected $table = 'pt_download';
    /**
     * 获取待下载种子
     */
    public static function getDownloadList(){
        $data = [];
        $list = self::where('status',0)
            ->select(['pthome_id'])
            ->get();
        $result = self::rssRequest();
        foreach ($list as $k =>$v){
            foreach ($result as $k2 =>$v2){
                if($v['pthome_id'] == $v2['id']){
                    $data[] = $v2['download_url'];
                }
            }
        }
        return $data;
    }
    /**
     * 请求rss地址 并处理数据后返回
     */
    public static function rssRequest(){
        $url = admin_config('pthome_rss_url');
        $result = @json_decode(json_encode(simplexml_load_string(file_get_contents($url), 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if(empty($result)){
            send_email('pthome','请求rss列表失败');
            return false;
        }
        $data = [];
        foreach ($result['channel']['item'] as $k => $v) {
            if(!empty($v['enclosure']['@attributes']['url'])){
                $url_data = parse_url($v['enclosure']['@attributes']['url']);
                if(empty($url_data['query'])){
                    continue;
                }
                parse_str($url_data['query'],$url_query_data);
                if(empty($url_query_data['id'])){
                    continue;
                }
                $data[] = [
                    'id'=>$url_query_data['id'],
                    'download_url'=>$v['enclosure']['@attributes']['url'],
                ];
            }
        }
        return $data;
    }
}
