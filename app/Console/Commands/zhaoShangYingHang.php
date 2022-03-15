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
        curl_setopt($curl, CURLOPT_POSTFIELDS, "Command=GETBTNSTATUS&ClientNo=42bd22fb34e64864a3443552805ae860&XRIPINN=ZY030101&XSAACOD=D07&UserInfo=AcWml6SlbJN1JxlmrA5za3nW%2BB3nIkdTNBxNjDoLffGyr4UlP%2BJMGuQulIQ%2FQEr%2FNQRF4uQo8ay47IgUcbALIMyiTpt6OOo6dbkEVDok3rJqJYsaj2Txw0pmod4McP0EHjGI5ozWE1yaGnuWFrQeYjnbCit7LdPBDhKJ7QU0RJyyIzWLlue4jBhMej2jOG4VWX45qZxVNHOKG8ie9HI8ve81uR5tmSPRV100i0LsqDxtjYhj%2F746GwZszqUW1WshmQM5FOd4Xq5K9RhrE%2Fj18gBnKRp2V0HnakXCOHw3622WrobCwhHX3buL%2BviToD%2FSzF16YgWK8w%2BB2WFxHCwcRGbOI6Dbx7op%2FrGQaeJExAJlzsqZk3h4iSY5RGAtRFpFC3Cr9HtAU1Yro8StBCHdkiEjOB3NQ5v7x3SBmgKZEe%2BjqWgUU2G1sOL9fq7SP%2Fc76yk8nNFtLipAL96O4cd%2BG0b3UGnVKMbcE96NziIrvM3TmqBbu5d%2BEwtuuomkwknPW56i0z9A%2FmqpH345c4yPPb6pBKGCZ5JDu11rn5OiX2NzsBLxJBnmZW2nis%2BBKt6CQBeiXFzpUBtYKVIohtc1Vu5maSIfbJrqKCfWMwYdNjQktx4C3eYUvj46vdQtlVyhiDweu2UlevytKukkQ8xBVWhOXj5MleDokqWpMNbWNK7kRMdsnCteSsQchd%2FFhgDJj5ne88zmNlrCPPbpt9AlPaaBw3lKuCkzALrlnqUCnLRpDUUZkzfJ6NYbhjvho%2FCRM61ix7EjXYM5NpXRBJ5E2U89qMP6ibvwANl%2FLCxzNSFwgHca5DWTCfIRyL0RC8jodvWXNhA04%2Bqc4liodic7G%2B%2FuUMtSc1Xh1CgR2weeLJNY2pLLda0%2FvAnmLb4wSQ%2BtWxlVe3OdDRN2CL6WDEi7U%2B99rfmlowh%2BsUjXGW7dV0AG%2F6VjdpkMIe6AI8sAwzphUXk7yaNIgckU58PL9E7S%2FfoQKJNrNTi%2FDCaCQmGhaQ%2BFZT%2BTLLootnVP%2BGJC71G4&EncType=S&ZSALTAG=A&ZSALBDT=2021-01-19&ZSALBTM=00%3A00%3A00&ZSALEDT=2120-01-19&ZSALETM=23%3A59%3A59&ZCCYNBR=%E5%85%83&ZJJBTAG=&%24RequestMode%24=1");
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);

        if(empty($data = json_decode($res,true))){
            send_email('招商银行',"返回结果格式错误 不是json res:".$res);
            $this->error("返回结果格式错误 不是json");
        }

        if(empty($data['$SysResult$']['$Content$']['DataSource']) || empty($data['$SysResult$']['$Content$']['DataSource']['btnTag']) || empty($data['$SysResult$']['$Content$']['DataSource']['btnTips'])){
            send_email('招商银行',"返回结果格式错误 DataSource字段不存在 res:".$res);
            $this->error("返回结果格式错误 字段不存在");
        }

        if($data['$SysResult$']['$Content$']['DataSource']['btnTag'] == 'Y'){
            send_email('招商银行',"理财有额度提醒：".$data['$SysResult$']['$Content$']['DataSource']['btnTips']);
            $this->info("理财有额度提醒：".$data['$SysResult$']['$Content$']['DataSource']['btnTips']);
        }
    }
}
