<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class pthome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pthome';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pthome爬种子';

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
        $url = 'https://pthome.net/torrents.php';
        $cookie = '__cfduid=d8ad58b735d8691091ae5b0c8d426eebd1600157895; UM_distinctid=17490d7cd41147-00576c4a65d659-333769-1fa400-17490d7cd4233b; c_secure_uid=MTI0MjE4; c_secure_pass=c217395a539744012d0b47933e5ce56f; c_secure_ssl=eWVhaA%3D%3D; c_secure_tracker_ssl=eWVhaA%3D%3D; c_secure_login=bm9wZQ%3D%3D; CNZZDATA1275677506=2056688231-1600665412-https%253A%252F%252Fmail.qq.com%252F%7C1600765047';
        $header = [
            'cookie:'.$cookie
        ];
        $result = curl_get($url,$header);
        echo $result;
        echo strpos($result,'<table class="torrents torrents-table"');
    }
}
