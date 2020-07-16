<?php
namespace app\api\controller;

use think\Db;
use think\cache\driver\Redis;

class Translate extends Check{
    public function index(){
    	$text = input('text');
    	$type = input('type');
    	$type_bool = false;
    	//合法的翻译类型
    	$type_arr = ['en','cht','yue','wyw','jb','kor','fra','spa','th','ara','ru','pt','de','it','el','nl','pl','bul','est','dan','cs','rom','slo','swe','hu','vie'];
    	foreach ($type_arr as $v){
    		$type != $v || $type_bool = true; 
    	}
    	!empty($type_bool) || msg(0,'非法的翻译类型');
		//使用正则匹配出字符串内所有的汉字
		preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$text,$res);
		if(empty($res[0])){
			msg(2,'输入的字符无中文');
		}
		//遍历获取字符串的长度，形成一个新的二维数组
		foreach ($res[0] as $k => $v) {
			$arr[$k] = ['len'=>strlen($v),'value'=>$v];
		}
		//根据二维数组的len字段倒序 防止bug!!!
		$tmp_arr = array_column($arr,'len');
		array_multisort($tmp_arr,SORT_DESC,$arr);
		//初始化字符串 并拼接多个需要翻译的语句 多个语句之间用\n分割
		$str = '';
		foreach ($arr as $k => $v) {
			$str = $str.$v['value']."\n";
		}
		//执行翻译操作，返回数组
		$arr = \BaiduTranslate::translate($str,'zh',input('type'));
		//zh简体中文
		//en英文
		//cht繁体中文
		if(empty($arr['trans_result'])){
			msg(0,'翻译过快或出错,请稍后重试');
		}
		//遍历 用翻译后的结果去替换汉字
		foreach ($arr['trans_result'] as $k => $v) {
			$text = str_replace($v['src'],$v['dst'],$text);
		}
		showjson(['status'=>1,'text'=>$text,'msg'=>'翻译成功']);
    }
}