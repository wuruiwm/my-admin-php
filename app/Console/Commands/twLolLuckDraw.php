<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LuckDrawLog;

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
        $result = $this->request();
        $data = @json_decode($result,true);
        if(empty($data)){
            send_email('台服lol幸运抽奖','请求错误');
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
                send_email('台服lol幸运抽奖','用户鉴权失败 请更新sk');
                $this->error('用户鉴权失败 请更新sk');
            //其他错误
            }else{
                send_email('台服lol幸运抽奖','其他错误:'.$data['detail']);
                $this->error('其他错误:'.$data['detail']);
            }
            return;
        }

        //抽奖成功 将记录写入抽奖记录表
        $luck_draw_log = [
            'content'=>$data['result']['prize']['item']['name'],
            'original_content'=>json_encode($data),
        ];
        LuckDrawLog::create($luck_draw_log);
        $this->info('抽奖成功 奖品:'.$data['result']['prize']['item']['name']);
    }
    /**
     * 请求抽奖接口返回结果
     */
    public function request(){
        $post_data = [
            'game'=>'lol',
            'sk'=>admin_config('tw_lol_luck_draw_sk'),
            'region'=>'TW',
            'version'=>1599462082,
            'tid'=>time(),
        ];
        return curl_post($this->lucky_draw_url,$post_data);
    }
}
