<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ssl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SSL保存到证书路径和缓存 并重启nginx';

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
        try {
            //读取文件 存入缓存
            $key = file_get_contents(admin_config('ssl_key'));
            $pem = file_get_contents(admin_config('ssl_pem'));
            Cache::put('ssl_key',$key);
            Cache::put('ssl_pem',$pem);
            $this->info("执行成功");
        } catch (\Throwable $th) {
            send_email('ssl证书自动更新异常','执行失败');
            $this->error("执行失败");
        }
    }
}
