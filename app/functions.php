<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-25 16:17:43
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 11:31:37
 */

//读取后台配置
function admin_config($key){
    $data = \Illuminate\Support\Facades\Cache::rememberForever('admin_config', function(){
        return \App\Models\Configuration::pluck('val','key');
    });
    if(isset($data[$key])){
        return $data[$key];
    }else{
        return '';
    }
}
//获取用户ip地址
function get_client_ip(){
    $ip = FALSE;
    //客户端IP 或 NONE
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = FALSE;
        }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    //客户端IP 或 (最后一个)代理服务器 IP
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
//获取当前请求带协议头的域名  例如https://www.baidu.com
function domain_name(){
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}
//随机字符串
function get_rand_string($length = 32){
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}
//加密
function password_encrypt($str = '',$key = null){
    $key != null || $key = admin_config('password_key');
    return base64_encode(openssl_encrypt($str,'AES-128-ECB',$key,OPENSSL_RAW_DATA));
}
//解密
function password_decrypt($str,$key = null){
    $key != null || $key = admin_config('password_key');
    return openssl_decrypt(base64_decode($str),'AES-128-ECB',$key,OPENSSL_RAW_DATA);
}
//curl post form-data
function curl_post($url, $post){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
//curl get
function curl_get($url,$header = []){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!empty($header)){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;    //返回json对象
}
//发送邮件
function send_email($title,$content){
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        //服务器配置
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Host = admin_config('email_server_host');//SMTP服务器域名
        $mail->SMTPAuth = true;
        $mail->Username = admin_config('email_username');//用户名
        $mail->Password = admin_config('email_password');//密码
        $mail->SMTPSecure = admin_config('email_encrypt');//可选参数tls ssl
        $mail->Port = admin_config('email_port');//一般无加密25 ssl465 tls 587 具体看服务商端口
        $mail->setFrom(admin_config('email_username'), '后台管理系统');//发件人
        $mail->addAddress('wuruiwm@qq.com');//收件人
        $mail->Subject = $title;
        $mail->Body = $content;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
