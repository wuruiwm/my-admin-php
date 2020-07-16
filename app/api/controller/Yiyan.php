<?php
namespace app\api\controller;

use think\Db;
use think\Validate;

class Yiyan extends Check{
    public function index(){
        $t = input('t');//类型 具体看数据库字段备注
        $e = input('e');//返回数据格式 默认是json  text纯文本  js和blog都是函数加document.write  json格式  
        $where = '';
        if(!empty($t)){
            $where .= "type='".$t."'";
        }
        $count = Db::name('yiyan')->count('id');
        $number = mt_rand(0,$count-1);
        $data = Db::name('yiyan')->where($where)->limit($number,1)->select();
        if(!empty($data)){
            $data = $data[0];
        }else{
            msg(0,'数据错误,请重试');
        }
        if(!empty($e)){
            if ($e == 'text'){
                $content = $data['content']."      ————————".$data['by'];
            }else if($e == 'js'){
                $content = 'function lwlhitokoto(){document.write("'.$data['content'].'        ————————'.$data['by'].'");}';
            }else if($e == 'blog'){
                $content = 'function lwlhitokoto(){document.write("'.$data['content'].'");}';
            }else if($e == 'json'){
                $content = json_encode($data,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $content = json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        echo $content;
    }
}