<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-27 11:08:53
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 09:29:49
 */

namespace App\Http\Controllers\Web;

use App\Models\Frp;
use App\Models\Ssh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class IndexController extends BaseController
{
    public function index(Request $request){
        $domain_name = $_SERVER['HTTP_HOST'];
        //frp穿透内网chrome
        if($domain_name == 'chrome.nikm.cn'){
            redirect('http://chrome.nikm.cn:'.admin_config('frp_http_port').'/vnc.html',301);
        }
        //导航页
        if($domain_name == 'menu.nikm.cn'){
            return View::make('web.menu');
        }
        //webssh
        if($domain_name == 'ssh.nikm.cn'){
            $method = $request->method();
            if($method == 'GET'){
                //渲染webssh页面模板
                return View::make('web.ssh');
            }else if($method == 'POST'){
                $host = $request->input('host');
                $port = $request->input('port');
                $is_https = $request->input('is_https');
                $user = $request->input('user');
                $password = $request->input('password');
                if(empty($host)){
                    return self::error("请填写主机名");
                }
                if(empty($port)){
                    return self::error("请填写端口号");
                }
                if(empty($user)){
                    return self::error("请填写用户名");
                }
                if(empty($password)){
                    return self::error("请填写密码");
                }
                $host = Ssh::translateDomainIP($host);
                //如果是192.168.2开头，则跳转frp的webssh连回家  否则跳转服务器运行的webssh
                if(strpos($host,'192.168.2') !== false){
                    $url = 'http://'.urlencode($user).':'.urlencode($password).'@'.$domain_name.':'.admin_config('frp_http_port').'/ssh/host/'.$host.'?port='.$port;
                }else{
                    $url = admin_config('ssh_connect_url').'/?hostname='.$host.'&username='.urlencode($user).'&password='.base64_encode($password).'&port='.$port;
                }
                return self::success('跳转成功',null,null,['url'=>$url]);
            }
        }
        //frp域名跳转
        if(!empty($data = Frp::where('domain_name',$domain_name)->select('is_https')->first())){
            if(!empty($data->is_https)){
                $url = "https://$domain_name:" . admin_config('frp_https_port');
            }else{
                $url = "http://$domain_name:" . admin_config('frp_http_port');
            }
            return redirect($url,301);
        }
        //其他跳转到后台登录页
        return redirect('/admin',302);
    }
}
