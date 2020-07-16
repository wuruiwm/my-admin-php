<?php
namespace app\api\controller;

use think\Db;
require(__DIR__.'/../../../extend/autoload.php');
class Baidupan extends Check{
    public function index(){
    	$url = input('url');
    	try {
    		$qrcode = new \Zxing\QrReader('https://pan.baidu.com/share/qrcode?&url='.$url);
		$text = $qrcode->text(); //返回二维码的内容
		$arr = @parse_url($text);
		if(empty($arr)){
			exit('识别出错,请重试');
		}
		$queryParts = @explode('&', $arr['query']); 
	    $params = array(); 
	    foreach ($queryParts as $param) 
	    { 
	        $item = explode('=', $param); 
	        $params[$item[0]] = $item[1]; 
	    }
	    if(empty($params['passwd'])){
	    	exit('密码为空');
	    }
		echo $params['passwd'];
    	} catch (\Exception $e) {
    		exit('不是正确的百度网盘分享地址');
    	}
    }
}