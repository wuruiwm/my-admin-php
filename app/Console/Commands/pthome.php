<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PtDownload;

class pthome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pthome {cli}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pthome';

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
        $command = $this->argument('cli');

        if(!in_array($command,['rss'])){
            $this->error("错误命令");
            $this->info("正确命令列表");
            $this->info("更新rss下载列表:php artisan pthome rss");
            return;
        }

        $this->$command();
    }
    /**
     * 更新rss下载列表
     */
    private function rss(){
        $data = PtDownload::rssRequest();
        if(empty($data)){
            $this->info('rss列表无数据');
            return;
        }
        //开始插入，并且计成功插入数量
        $success = 0;
        foreach ($data as $k =>$v){
            try {
                $pt_download = PtDownload::where('id',$v['id'])->select(['id'])->first();
                if(!empty($pt_download)){
                    continue;
                }
                PtDownload::create($v);
                $success++;
            } catch (\Throwable $th) {
            }
        }
        if($success == 0){
            $this->info('无新增的种子');
            return;
        }
        $this->info('成功新增'.$success.'个种子');
        send_email('pthome','成功新增'.$success.'个种子');
    }
}
