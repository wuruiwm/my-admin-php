<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class shengxianchuanqi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shengxianchuanqi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生鲜传奇自动签到';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $sign_in_url = 'https://minih5.retailo2o.com/sxcq/coc/do?_platform_num=6&cid=4563';

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
        $header = [
            'cookie:'.admin_config('shengxianchuanqi_cookie')
        ];
        $result = curl_get($this->sign_in_url,$header);
        $data = @json_decode($result,true);
        if(empty($data)){
            send_email('生鲜传奇签到','请求错误');
            $this->error('请求错误');
            return;
        }
        if($data['code'] == 1001){
            send_email('生鲜传奇签到','签到成功');
            $this->info('签到成功');
        }else{
            send_email('生鲜传奇签到','签到失败 error:'.$data['message']);
            $this->error('签到失败 error:'.$data['message']);
        }
    }
}
