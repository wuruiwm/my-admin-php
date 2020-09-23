<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $post_data = [
            'game'=>'lol',
            'sk'=>admin_config('tw_lol_luck_draw_sk'),
            'region'=>'TW',
            'version'=>1599462082,
            'tid'=>time(),
        ];
        $result = curl_post($this->lucky_draw_url,$post_data);
        $data = @json_decode($result,true);
        if(empty($data)){
            $this->error('请求错误');
            return;
        }
        $this->info(json_encode($data));
    }
}
