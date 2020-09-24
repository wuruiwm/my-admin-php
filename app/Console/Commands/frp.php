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
     * frp启动命令
     *
     * @var string
     */
    protected $frp_start = 'nohup /root/frp/frps -c /root/frp/frps.ini >/dev/null 2>&1 &';

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

        if(!in_array($command,['guard','restart'])){
            $this->error("错误命令");
            $this->info("正确命令列表");
            $this->info("守护:php artisan frp guard");
            $this->info("重启:php artisan frp restart");
            return;
        }

        $shell = "ps -aux|grep frp| grep -v grep";
        exec($shell, $result, $status);

        //守护
        if($command == 'guard'){
            $this->guard();
        //重启
        }else if($command == 'restart'){
            $this->restart();
        }
    }
    /**
     * frp守护
     */
    private function guard(){
        $result = $this->grepResult();
        $bool = true;
        foreach ($result as $k => $v) {
            if (strpos($v,'frps') !== false) {
                $bool = false;
                $this->info("frp运行正常");
            }
        }
        $this->start($bool,'检测到frp被关闭,已重启');
    }
    /**
     * 获取查找frp进程结果
     */
    private function grepResult(){
        $shell = "ps -aux|grep frp| grep -v grep";
        exec($shell, $result, $status);
        return $result;
    }
    /**
     * frp重启
     */
    private function restart(){
        $result = $this->grepResult();
        $bool = true;
        foreach ($result as $k => $v) {
            if (strpos($v,'frps') !== false) {
                $frp_pid_res = substr($v,0,16);
                $frp_pid = str_replace('root','',$frp_pid_res);
                $this->killFrp(trim($frp_pid));
                $this->start($bool,'已重新启动frp');
            }
        }
        $this->start($bool,'检测到frp没有运行，启动frp');
    }
    /**
     * 判断bool值来启动
     * @param bool $bool 为真时启动frp
     * @param string $msg 输出提示
     */
    private function start($bool = true,$msg = '启动frp'){
        if($bool == true){
            $shell = $this->frp_start;
            exec($shell, $result, $status);
            $this->info($msg);
            send_email('frp守护',$msg);
        }
    }
    /**
     * 杀死frp进程
     * @param int $pid frp进程pid
     */
    private function killFrp(int $pid){
        $shell = "kill -9 ".$pid;
        exec($shell, $result, $status);
    }
}
