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
    protected $signature = 'hitokoto {num}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬取一言数据';

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
        //获取需要爬取的条数
        $num = $this->argument('num');
        $num = intval($num);
        $success = 0;
        $i = 0;
        while($i < $num){
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
                $success++;
                $this->info("插入成功");
            } catch (\Throwable $th) {
                $this->error("插入失败");
            }
            $i++;
        }
        $this->info('有效插入'.$success.'条');
    }
}
