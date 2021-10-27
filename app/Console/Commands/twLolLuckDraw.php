<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LuckDrawLog;
use Illuminate\Support\Facades\Cache;

class twLolLuckDraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twLolLuckDraw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '台服lol幸运抽奖';

    /**
     * 台服lol抽奖url
     *
     * @var string
     */
    protected $lucky_draw_url = 'https://luckydraw.gamehub.garena.tw/service/luckydraw';

    /**
     * 当天请求错误几次后发送通知
     *
     * @var string
     */
    protected $tw_lol_luck_draw_error_day_num = 3;

    /**
     * 幸运抽奖的版本号
     *
     * @var string
     */
    protected $version;

    /**
     * 用户鉴权sk
     *
     * @var string
     */
    protected $sk;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->initSk();
        $this->initVersion();
        $result = $this->request();
        $data = @json_decode($result,true);
        if(empty($data)){
            $this->errorSendEmail('台服lol幸运抽奖','请求错误');
            $this->error('请求错误');
            return;
        }
        //有error 则代表有错误
        if(!empty($data['error'])){
            //20 抽奖CD中...
            if($data['error'] == 20){
                $this->info('抽奖CD中...');
            //用户鉴权失败 请更新sk
            }else if($data['error'] == 11){
                $this->errorSendEmail('台服lol幸运抽奖','用户鉴权失败 请更新sk');
                $this->error('用户鉴权失败 请更新sk');
            //其他错误
            }else{
                $this->errorSendEmail('台服lol幸运抽奖','其他错误:'.$data['detail']);
                $this->error('其他错误:'.$data['detail']);
            }
            return;
        }

        //抽奖成功 将记录写入抽奖记录表
        $luck_draw_log = [
            'content'=>$data['result']['prize']['item']['name'],
            'original_content'=>json_encode($data),
        ];
        try {
            LuckDrawLog::create($luck_draw_log);
            send_email('台服lol幸运抽奖','抽奖成功 奖品:'.$data['result']['prize']['item']['name']);
            $this->info('抽奖成功 奖品:'.$data['result']['prize']['item']['name']);
        } catch (\Throwable $th) {
            $this->errorSendEmail('台服lol幸运抽奖','抽奖成功，但是插入数据失败');
            $this->error("抽奖成功，但是插入数据失败");
        }
    }
    /**
     * 请求抽奖接口返回结果
     */
    public function request(){
        $post_data = [
            'game'=>'lol',
            'sk'=>$this->sk,
            'region'=>'TW',
            'version'=>$this->version,
            'tid'=>time(),
        ];
        return curl_post($this->lucky_draw_url,$post_data);
    }
    /**
     * 初始化幸运抽奖的版本号
     */
    public function initVersion(){
        $url = 'https://luckydraw.gamehub.garena.tw/service/luckydraw/?sk='.$this->sk.'&region=TW&tid='.time();
        var_dump($this->curl_get_https($url),'https://luckydraw.gamehub.garena.tw/service/luckydraw/?sk='.$this->sk.'&region=TW&tid='.time());exit();
        $result = @json_decode(@file_get_contents($url),true);
        if(empty($result)){
            exit('请求获取抽奖版本号接口失败');
        }
        if(!empty($result['error'])){
            if($result['error'] == 11){
                $this->errorSendEmail('台服lol幸运抽奖','用户鉴权失败 请更新sk');
                exit('用户鉴权失败 请更新sk');
            }else{
                $this->errorSendEmail('台服lol幸运抽奖','其他错误:'.$result['datail']);
                exit('其他错误:'.$result['datail']);
            }
        }
        $version = false;
        foreach($result['result']['settings'] as $k =>$v){
            if($v['code'] == 'lol'){
                $version = $v['version'];
            }
        }
        if(empty($version)){
            exit('获取抽奖版本号失败');
        }
        $this->version = $version;
    }
    public function initSk(){
        if(!empty($GLOBALS['tw_lol_sk'])){
            $sk = $GLOBALS['tw_lol_sk'];
        }else{
            $sk = admin_config('tw_lol_luck_draw_sk');
        }
        if(empty($sk)){
            exit('获取用户鉴权sk失败');
        }
        $this->sk = $sk;
    }
    /**
     * 一天内失败达到N次时 发送邮件提醒
     */
    public function errorSendEmail($title,$content){
        $num_cache_key = 'tw_lol_luck_draw_error_day_num';
        $send_time_cache_key = 'tw_lol_luck_draw_error_send_time';
        $num = Cache::get($num_cache_key);
        if(empty($num)){
            Cache::put($num_cache_key,1,get_day_surplus_second());
        }else if($num >= $this->tw_lol_luck_draw_error_day_num && is_send_notice() && empty(Cache::get($send_time_cache_key))){
            send_email($title,$content);
            Cache::put($send_time_cache_key,1,60*60*6);
        }else{
            Cache::increment($num_cache_key);
        }
    }
    public function curl_get_https($url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }
}
