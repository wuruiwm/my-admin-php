<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class pushBlogLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushBlogLink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送博客文章链接到百度SEO';

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
        //连接博客数据库查询文章表
        $contents_list = DB::connection('blog_mysql')->table('contents')->get();
        //处理数据
        $url_list = [];
        foreach ($contents_list as $k =>$v){
            if($v->cid == $v->slug){
                $url_list[] = 'https://www.nikm.cn/archives/'.$v->cid.'.html';
            }
        }
        //开始推送
        $api = 'http://data.zz.baidu.com/urls?site=https://www.nikm.cn&token=Wn8c18vT1naD8X2K';
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $url_list),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result,true);
        $this->info('成功推送'.$result['success'].'条数据，当天剩余可推送条数为'.$result['remain'].'条');
    }
}
