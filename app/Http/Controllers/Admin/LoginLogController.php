<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-27 11:08:53
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 09:29:49
 */

namespace App\Http\Controllers\Admin;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class LoginLogController extends BaseController
{
    /**
     * 登录日志主页
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.log.login');
    }

    /**
     * 数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $data = $request->all(['created_at_start','created_at_end','username']);
        $res = LoginLog::when($data['username'],function ($query,$data){
                return $query->where('username','like','%'.$data['username'].'%');
            })->when($data['created_at_start']&&!$data['created_at_end'],function ($query,$data){
                return $query->where('created_at','>=',$data['created_at_start']);
            })->when(!$data['created_at_start']&&$data['created_at_end'],function ($query,$data){
                return $query->where('created_at','<=',$data['created_at_end']);
            })->when($data['created_at_start']&&$data['created_at_end'],function ($query,$data){
                return $query->whereBetween('created_at',[$data['created_at_start'],$data['created_at_end']]);
            })->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }

        if (empty(adminConfig('delete_login_log'))){
            return Response::json(['code'=>1,'msg'=>'系统已设置禁止删除登录日志']);
        }
        try{
            LoginLog::destroy($ids);
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }

}
