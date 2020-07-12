<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class frp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frp {cli}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'FRP相关操作';

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

        $shell = "ps -aux|grep frp| grep -v grep";
        exec($shell, $result, $status);
        $status = true;

        //守护
        if($command == 'guard'){
            foreach ($result as $k => $v) {
                if (strpos($v,'frps') !== false) {
                    $status = false;
                    $this->info("frp运行正常");
                }
            }
            if($status == true){
                $shell = "nohup /root/frp/frps -c /root/frp/frps.ini >/dev/null 2>&1 &";
                exec($shell, $result, $status);
                $this->error("检测到frp被关闭,已重启");
            }
        //重启
        }else if($command == 'restart'){
            foreach ($result as $k => $v) {
                if (strpos($v,'frps') !== false) {
                    $frp_pid_res = substr($v,0,16);
                    $frp_pid = str_replace('root','',$frp_pid_res);
                    $frp_pid = trim($frp_pid);
                    $shell = "kill -9 ".$frp_pid;
                    exec($shell, $result, $status);
                    $shell = "nohup /root/frp/frps -c /root/frp/frps.ini >/dev/null 2>&1 &";
                    exec($shell, $result, $status);
                    $status = false;
                    $this->info("已重新启动frp");
                }
            }
            if($status == true){
                $shell = "nohup /root/frp/frps -c /root/frp/frps.ini >/dev/null 2>&1 &";
                exec($shell, $result, $status);
                $this->info("检测到frp没有运行，启动frp");
            }
        }else{
            $this->error("错误命令");
            $this->info("正确命令列表");
            $this->info("守护:php artisan frp guard");
            $this->info("重启:php artisan frp restart");
        }
    }
}
