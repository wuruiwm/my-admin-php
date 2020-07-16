<?php
// 应用公共文件
/**
 * 根据附件表的id返回url地址
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function geturl($id){
	if ($id) {
		$geturl = \think\Db::name("attachment")->where(['id' => $id])->find();
		if($geturl['status'] == 1) {
			//审核通过
			return $geturl['filepath'];
		} elseif($geturl['status'] == 0) {
			//待审核
			return '/uploads/xitong/beiyong1.jpg';
		} else {
			//不通过
			return '/uploads/xitong/beiyong2.jpg';
		}
    }
    return false;
}
//返回status为0的状态码加提示信息
function msg($status = 0,$msg = ''){
	exit(json_encode(['status'=>$status,'msg'=>$msg],JSON_UNESCAPED_UNICODE));
}
//返回json数据
function showjson($data = []){
	exit(json_encode($data,JSON_UNESCAPED_UNICODE));
}
function yiyan_type($type = 'g'){
	//类型 a动画 b漫画 c游戏 d小说 e原创 f来自网络 g其他
	if($type == 'a'){
		return '动画';
	}else if($type == 'b'){
		return '漫画';
	}else if($type == 'c'){
		return '游戏';
	}else if($type == 'd'){
		return '小说';
	}else if($type == 'e'){
		return '原创';
	}else if($type == 'f'){
		return '来自网络';
	}else{
		return '其他';
	}
}
//获取当前请求带协议头的域名  例如https://www.baidu.com
function domain_name(){
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}
//301重定向
function http301($url = ''){
	header('HTTP/1.1 301 Moved Permanently');
	header("location:$url");
	exit();
}
//获取用户ip
function get_client_ip(){
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	}
	if (getenv('HTTP_X_REAL_IP')) {
		$ip = getenv('HTTP_X_REAL_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
		$ips = explode(',', $ip);
		$ip = $ips[0];
	} elseif (getenv('REMOTE_ADDR')) {
		$ip = getenv('REMOTE_ADDR');
	} else {
		$ip = '0.0.0.0';
	}
	return $ip;
}
//获取page和limit
function page(){
	$page = input('page');
	$limit = input('limit');
	if (empty($page) || !is_numeric($page)) {
		msg(0,'请输入正确的页码');
	}
	if (empty($limit) || !is_numeric($limit)) {
		msg(0,'请输入正确的条数');
	}
	$page = intval($page);
	$limit = intval($limit);
	$number = ($page - 1) * $limit;
	$data = ['number'=>$number,'limit'=>$limit];
	return $data;
}
//格式化二维数组的时间戳 参数1为二维数组 参数2为时间戳的字段可以为多个，数组传入 参数3为非必填 date的格式化字符串
function array_date($data = [],$field = [],$string = 'Y/n/j G:i:s'){
	foreach ($data as $k => $v) {
		foreach($field as $k2 => $v2){
			$data[$k][$v2] = date($string,$v[$v2]);
		}
	}
	return $data;
}
//获取id，通常用于删除
function input_id(){
	$id = input('id');
	if (empty($id) || !is_numeric($id)) {
		msg(0,'请传入正确的id');
	}
	$id = intval($id);
	return $id;
}
//数据校验 参数1 验证规则  参数2 验证错误返回值
function check($rule,$msg){
	$post = input('post.');
	$validate = new think\Validate($rule,$msg);
	if (!$validate->check($post)) {
		msg(0,$validate->getError());
	}
	return $post;
}
//将一个字符串的域名或者ip提取出来
function domain_ip($string){
	$string = trim($string);
	if(filter_var($string,FILTER_VALIDATE_IP)){
		$host = $string;
	}else if(!empty(parse_url($string)['host'])){
		$host = parse_url($string)['host'];
	}else{
		$host = $string;
	}
	return $host;
}
//返回指定位数的随机字符串
function getRandomChar($length){
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}
//加密
function encrypt($str){
    return base64_encode(openssl_encrypt($str,'AES-128-ECB',config('key'),OPENSSL_RAW_DATA));
}
//解密
function decrypt($str){
    return openssl_decrypt(base64_decode($str),'AES-128-ECB',config('key'),OPENSSL_RAW_DATA);
}