<?php
namespace App\Models;

use Illuminate\Support\Facades\Cache;

class PtDownload extends Base
{
    protected $table = 'pt_download';
    const send_mail_day_num = 3;//当天请求错误几次后发送通知
    /**
     * 获取待下载种子
     */
    public static function getDownloadList(){
        $result = self::rssInsert();
        $data = [];
        $list = self::where('status',0)
            ->select(['pthome_id'])
            ->get();
        foreach ($list as $k =>$v){
            foreach ($result as $k2 =>$v2){
                if($v['pthome_id'] == $v2['id']){
                    $data[] = [
                        'pthome_id'=>$v['pthome_id'],
                        'download_url'=>$v2['download_url'],
                    ];
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
            self::errorSendEmail('pthome','请求rss列表失败');
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
    /**
     * rss列表更新的种子 插入数据库
     */
    public static function rssInsert(){
        $data = self::rssRequest();
        if(empty($data)){
            return $data;
        }
        //开始插入，并且计成功插入数量
        $success = 0;
        foreach ($data as $k =>$v){
            try {
                $pt_download = PtDownload::where('pthome_id',$v['id'])->select(['id'])->first();
                if(!empty($pt_download)){
                    continue;
                }
                $v['status'] = 0;
                $v['pthome_id'] = $v['id'];
                unset($v['download_url']);
                unset($v['id']);
                PtDownload::create($v);
                $success++;
            } catch (\Throwable $th) {
            }
        }
        if($success == 0){
            return $data;
        }
        send_email('pthome','成功新增'.$success.'个种子');
        return $data;
    }
    /**
     * 一天内失败达到N次时 发送邮件提醒
     */
    public static function errorSendEmail($title,$content){
        $num_cache_key = 'pthome_error_day_num';
        $send_time_cache_key = 'pthome_error_send_time';
        $num = Cache::get($num_cache_key);
        if(empty($num)){
            Cache::put($num_cache_key,1,get_day_surplus_second());
        }else if($num >= self::send_mail_day_num && is_send_notice() && empty(Cache::get($send_time_cache_key))){
            send_email($title,$content);
            Cache::put($send_time_cache_key,1,60*60*6);
        }else{
            Cache::increment($num_cache_key);
        }
    }
}
