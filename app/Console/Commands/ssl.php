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
    protected $description = 'ssl';

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
            $key = file_get_contents(admin_config('ssl_key'));
            $pem = file_get_contents(admin_config('ssl_pem'));
            Cache::put('ssl_key',$key);
            Cache::put('ssl_pem',$pem);
            $this->info("读取证书成功");
        } catch (\Throwable $th) {
            $this->error("读取证书失败");
        }
    }
}
