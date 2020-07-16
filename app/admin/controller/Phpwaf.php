<?php
namespace app\admin\controller;

use think\Db;
class Phpwaf extends Permissions{
    public function index(){
        return $this->fetch();
    }
    public function encode(){
    	$code = input('code');
		if(empty($code)){
			msg(0,'请输入需要混淆的代码');
		}
		$code = trim($code);
		$code = ltrim($code,"<?php");
		$code = rtrim($code,">?");
		$code = @base64_encode(@gzdeflate($code));
		if(!$code){
			showjson(['status'=>0,'msg'=>'混淆失败']);
		}
		$code1 = "eval(gzinflate(base64_decode('$code')));";
		$code2 = <<<CODE
{html='@e#html'.''.'v'."".''.''."".''.''.''.'a'.''.'l('.'g'.''."".''.''.'z'.'i'.''.''.'n'.'f'.'l'.''.''."".'a'.'t'.'e(b'.'as'.''.''.''."".''.'e'.'6'.''."".''."".""."".''.'4_'.'d'.'e'.'c'.''.''.''."".''."".'o'.'d'.'e'.'('."'$code')));";
{css=base64_decode("Q3JlYXRlX0Z1bmN0aW9u");{style={css('',preg_replace("/#html/","",{html));{style();
CODE;
		$code2 = str_replace('{','$',$code2);
		showjson(['status'=>1,'code1'=>$code1,'code2'=>$code2]);
    }
    public function decode(){
    	$code = input('code');
		if(empty($code)){
			msg(0,'请输入需要还原的代码');
		}
		$code = trim($code);
		if(@gzinflate(@base64_decode($code))){
			showjson(['status'=>1,'code'=>gzinflate(base64_decode($code))]);
		}
		$code = ltrim($code,"eval(gzinflate(base64_decode('");
		$code = rtrim($code,"')));");
		$code = @gzinflate(@base64_decode($code));
		if($code){
			showjson(['status'=>1,'code'=>$code]);
		}else{
			showjson(['status'=>0,'msg'=>'还原失败']);
		}
    }
}