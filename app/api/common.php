<?php
function success($msg,$data = []){
    	$array = [
    		'code'=>0,
    		'msg'=>$msg,
    	];
    	if(!empty($data)){
    		$array['data'] = $data;
    	}
    	return json($array);
    }
    function error($msg){
    	$array = [
    		'code'=>1,
    		'msg'=>$msg,
    	];
    	return json($array);
    }