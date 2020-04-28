<?php
namespace App\Models;

class Translate extends Base
{
    public static $CURL_TIMEOUT = 10;
    public static $URL = "http://api.fanyi.baidu.com/api/trans/vip/translate";
    public static $APP_ID;
    public static $SEC_KEY;
    public static function language(){
        $language_string = admin_config('baidu_translate_language');
        $language_array = explode("\n",$language_string);
        $data = [];
        foreach($language_array as $k => $v){
            $tmp_array = explode('|',$v);
            if(!empty($tmp_array) && count($tmp_array) == 2){
                $data[$tmp_array[0]] = $tmp_array[1];
            }
        }
        return $data;
    }
    public static function languageValidationInSting(){
        $data = self::language();
        $string_in = '';
        foreach ($data as $k =>$v){
            $string_in .= $k . ',';
        }
        return rtrim($string_in,",");
    }
    public static function getData($text){
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$text,$res);
        if(empty($res[0])){
            return false;
        }
        //遍历获取字符串的长度，形成一个新的二维数组
        foreach ($res[0] as $k => $v) {
            $arr[$k] = ['len'=>strlen($v),'value'=>$v];
        }
        //根据二维数组的len字段倒序 防止bug!!!
        $tmp_arr = array_column($arr,'len');
        array_multisort($tmp_arr,SORT_DESC,$arr);
        //拼接多个需要翻译的语句 多个语句之间用\n分割
        $str = '';
        foreach ($arr as $k => $v) {
            $str = $str.$v['value']."\n";
        }
        return $str;
    }
    public static function replace($trans_result,$text){
        foreach ($trans_result as $k => $v) {
            $text = str_replace($v['src'],$v['dst'],$text);
        }
        return $text;
    }
    public static function translate($query, $from, $to) {
        self::$APP_ID = admin_config('baidu_translate_appid');
        self::$SEC_KEY = admin_config('baidu_translate_key');
        $args = array(
            'q' => $query,
            'appid' => self::$APP_ID,
            'salt' => mt_rand(10000, 99999),
            'from' => $from,
            'to' => $to,
        );
        $args['sign'] = self::buildSign($query, self::$APP_ID, $args['salt'], self::$SEC_KEY);
        $ret = self::call(self::$URL, $args);
        $ret = json_decode($ret, true);
        return $ret;
    }
    //加密
    public static function buildSign($query, $appID, $salt, $secKey) {
        $str = $appID . $query . $salt . $secKey;
        $ret = md5($str);
        return $ret;
    }
    //发起网络请求
    public static function call($url, $args = null, $method = "post", $testflag = 0, $timeout = '', $headers = array()) {
        if(empty($timeout)){
            $timeout = self::$CURL_TIMEOUT;
        }
        $ret = false;
        $i = 0;
        while ($ret === false) {
            if ($i > 1) break;

            if ($i > 0) {
                sleep(1);
            }
            $ret = self::callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }
        return $ret;
    }
    public static function callOnce($url, $args = null, $method = "post", $withCookie = false, $timeout = '', $headers = array()) {
        if(empty($timeout)){
            $timeout = self::$CURL_TIMEOUT;
        }
        $ch = curl_init();
        if ($method == "post") {
            $data = self::convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = self::convert($args);
            if ($data) {
                if (stripos($url, "?") > 0) {
                    $url.= "&$data";
                } else {
                    $url.= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
    public static function convert(&$args) {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data.= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data.= "$key=" . rawurlencode($val) . "&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}
