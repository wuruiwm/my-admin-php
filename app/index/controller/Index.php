<?php
namespace app\index\controller;

use \think\Db;
use \think\Controller;

class Index extends Controller
{
    public function index()
    {
        $host = $_SERVER['HTTP_HOST'];
        if($host == 'my.nikm.cn'){
            http301('https://my.nikm.cn/admin');
        }
        if($host == 'blog.wmxxxx.com' || $host == 'web.wmxxxx.com'){
            http301('https://nikm.cn');
        }
        if($host == 'wmxxxx.com' || $host == 'www.wmxxxx.com'){
            http301('https://menu.nikm.cn');
        }
        if(strpos($host,'wmxxxx.com') !== false){
            http301(str_replace("wmxxxx.com","nikm.cn",domain_name()));
        }
        if($host == 'chrome.nikm.cn'){
            http301('http://chrome.nikm.cn:999/vnc.html');
        }
        $data = Db::name('jump')->field(['domain_name','is_https'])->where('domain_name',$host)->find();
        //如果当前访问域名，不在jump表中，则返回导航页
        if(empty($data) || $data['domain_name'] == 'menu.nikm.cn'){
            return $this->fetch('menu');
        }
        //web ssh判断跳转，如果是192.168.2开头的ip，则跳转999端口，如果不是则跳转777端口
        if($data['domain_name'] == 'ssh.nikm.cn'){
            $post = input('post.');
            if (!empty($post)) {
                $host = domain_ip($post['host']);
                $port = $post['port'];
                if(empty($host) || empty($port)){
                	msg(0,'请填写主机名和端口号');
                }
                $url = $data['is_https'] ? 'https://' : 'http://';
                if(!empty($post['user']) && !empty($post['password'])){
                	$url .= urlencode($post['user']).':'.urlencode($post['password']).'@';
                }
                $url .= $data['domain_name'];
                strpos($host,'192.168.2') !== false ? $url .= ":999/ssh/host/".$host."?port=".$port : $url .= ":777/ssh/host/".$host."?port=".$port;
				$log = fopen(__DIR__."/../../ssh_log.txt", "a");
				$time = date("Y-m-d H:i:s");
				$txt = "主机名:$host 用户名:$post[user] 密码:$post[password] 端口号:$post[port] 登陆时间:$time\n-----------------------------------------------------------\n";
				fwrite($log, $txt);
				fclose($log);
				showjson(['status'=>1,'msg'=>'跳转成功','url'=>$url]);
                http301($url);
            }else{
                return $this->fetch('ssh');
            }
        }
        //判断是http还是https，跳转frp不同的端口
        $data['is_https'] ? $url = 'https://'.$data['domain_name'].':9999' : $url = 'http://'.$data['domain_name'].':999';
        http301($url);
    }
}