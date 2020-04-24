<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hitokoto as model;

class hitokoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hitokoto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hitokoto';

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
        while(true){
            $res = file_get_contents('https://v1.hitokoto.cn/');
            $res = json_decode($res,true);
            $data = [];
            $data['content'] = $res['hitokoto'];
            $data['id'] = $res['id'];
            !empty($res['created_at']) || $res['created_at'] = time();
            $data['created_at'] = date('Y-m-d H:i:s',$res['created_at']);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['type'] = model::typeTransformation($res['type']);
            try {
                model::insert($data);
                $this->info("插入成功");
            } catch (\Throwable $th) {
                $this->error("插入失败");
            }
        }
    }
}
