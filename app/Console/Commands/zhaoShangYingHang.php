<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class zhaoShangYingHang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zsyh {cli}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '招商银行';

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

        if(!in_array($command,['licai'])){
            $this->error("错误命令");
            $this->info("正确命令列表");
            $this->info("理财:php artisan zsyh licai");
            return;
        }

        $shell = "ps -aux|grep zsyh| grep -v grep";
        exec($shell, $result, $status);

        $this->$command();
    }
    /**
     * 理财可购买额度检测
     */
    private function licai(){
        //没额度格式 {"$SysResult$":{"$SysCode$": 500,"$Content$": {"ErrCode":"0","DataSource":{"btnTag":"N","btnTips":"很抱歉，该产品已售罄，试试查看 <span style=\"color: #5995ef;\" onclick=\"getRecList()\">其他产品<i class=\"iconfont icon-angleright bottom\"></i></span>","btnTxt":"可购买额度已售罄"}}}}
        //有额度格式 {"$SysResult$":{"$SysCode$": 500,"$Content$": {"ErrCode":"0","DataSource":{"btnTag":"Y","btnTips":"剩余可购买额度>5000万元","btnTxt":"购买"}}}}
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://mobile.cmbchina.com/IEntrustFinance/SubsidiaryProduct/SA_QueryAjax.aspx");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["cookie:".admin_config("zsyl_cookie"),"Content-Type:application/x-www-form-urlencoded; charset=UTF-8"]);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "Command=GETBTNSTATUS&ClientNo=174a7cf1a04a4f0cb0241ffefa8e4b40&AccountUID=SSQJJmYTpOsIjzpPt39%3AGMufy1lp%3AaXaqizZakQTZ9k_&XRIPINN=107333E&XSAACOD=D07&UserInfo=&EncType=S&ZSALTAG=A&ZSALBDT=2022-06-07&ZSALBTM=09%3A00%3A00&ZSALEDT=2022-06-09&ZSALETM=17%3A00%3A00&ZCCYNBR=%E5%85%83&ZJJBTAG=&%24RequestMode%24=1");
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);

        if(empty($data = json_decode($res,true))){
            send_email('招商银行',"返回结果格式错误 不是json res:".$res);
            $this->error("返回结果格式错误 不是json");
            return;
        }

        if(empty($data['$SysResult$']['$Content$']['DataSource']) || empty($data['$SysResult$']['$Content$']['DataSource']['btnTag']) || empty($data['$SysResult$']['$Content$']['DataSource']['btnTips'])){
            send_email('招商银行',"返回结果格式错误 DataSource字段不存在 res:".$res);
            $this->error("返回结果格式错误 字段不存在");
            return;
        }

        if($data['$SysResult$']['$Content$']['DataSource']['btnTag'] == 'Y'){
            send_email('招商银行',"理财有额度提醒：".$data['$SysResult$']['$Content$']['DataSource']['btnTips']);
            $this->info("理财有额度提醒：".$data['$SysResult$']['$Content$']['DataSource']['btnTips']);
        }else if($data['$SysResult$']['$Content$']['DataSource']['btnTag'] == 'N'){
            $this->info("理财无额度");
        }else{
            send_email('招商银行',"返回结果格式错误 字段数据异常 res:".$res);
            $this->error("返回结果格式错误 字段数据异常");
        }
    }
}
