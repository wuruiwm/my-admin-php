<?php
class BaiduTranslate{
	public static $CURL_TIMEOUT = 10;
	public static $URL = "http://api.fanyi.baidu.com/api/trans/vip/translate";
	public static $APP_ID = "20191224000369412";
	public static $SEC_KEY = "JzaEO_ZpODRCahXm5om7";
	public static function translate($query, $from, $to) {
	    $args = array(
	        'q' => $query,
	        'appid' => self::$APP_ID,
	        'salt' => rand(10000, 99999),
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
?>