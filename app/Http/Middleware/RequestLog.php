<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-01-06 10:53:03
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 11:30:51
 */

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Closure;

class RequestLog
{
    public function handle($request, Closure $next){
    	$log = "URL：". (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\n";
    	$log .= '参数：'.$this->jsonLog($request->all())."\n";
    	$log .= '请求：'.$this->jsonLog($this->headerFormat($request->header()))."\n";
		$sql = "";
		$sql_num = 0;
		$sql_time = 0;
        DB::listen(function($query) use(&$sql,&$sql_num,&$sql_time){
			$sql_tmp = $query->sql;
			foreach($query->bindings as $k =>$v){
				(($pos = strpos($sql_tmp,'?')) !== false) && $sql_tmp = substr_replace($sql_tmp,"'$v'",$pos,1);
			}
			$sql .= 'SQL：'.$sql_tmp.' 耗时：'.$query->time."ms\n";
			$sql_num++;
			$sql_time += $query->time;
        });
    	$response = $next($request);
    	$log .= "响应:".$this->jsonLog($response->original)."\n" . $sql . "执行" . $sql_num . "条sql 耗时:$sql_time" . "ms\n";
		Log::info($log);
        return $response;
	}
	protected function jsonLog($array = []){
		return json_encode($array,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
	}
	protected function headerFormat($array){
		foreach ($array as $k => $v) {
			$array[$k] = $v[0];
		}
		return $array;
	}
}
