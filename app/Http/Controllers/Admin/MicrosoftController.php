<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Models\Microsoft;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RequiredIdRequest;

class MicrosoftController extends BaseController
{
    public function index(){
        return View::make('admin.microsoft.index');
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $status = $request->input('status');
        $password = Microsoft::list($offset,$limit,$keyword,$status);
        return self::success('请求成功',$password['data'],$password['count']);
    }
    public function create(Request $request){
        $num = $request->input('num');
        $num = intval($num);
        if(empty($num) || $num <= 0){
            return self::error('请输入正确的邀请码生成数量');
        }
        $data = Microsoft::createAll($num);
        return self::success('生成成功',$data);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        $microsoft = Microsoft::where('id',$id)->first();
        if(empty($microsoft)){
            return self::error('邀请码不存在,删除失败');
        }
        if(!empty($microsoft->email)){
            Microsoft::getMicrosoftToken();
            $result = Microsoft::deleteAccount($microsoft->email);
            if(empty($result)){
                return self::error('无权限删除账户,删除失败');
            }
        }
        try {
            $microsoft->delete($id);
            return self::success('删除成功');
        } catch (\Throwable $th) {
            return self::error('删除失败');
        }
    }
    public function active(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        $microsoft = Microsoft::where('id',$id)->first();
        if(empty($microsoft)){
            return self::error('邀请码不存在,允许账户登录失败');
        }
        Microsoft::getMicrosoftToken();
        $result = Microsoft::activeAccount($microsoft->email);
        if(!empty($result)){
            return self::success('允许失败');
        }else{
            return self::success('允许成功');
        }
    }
    public function inactive(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        $microsoft = Microsoft::where('id',$id)->first();
        if(empty($microsoft)){
            return self::error('邀请码不存在,禁止账户登录失败');
        }
        Microsoft::getMicrosoftToken();
        $result = Microsoft::inactiveAccount($microsoft->email);
        if(!empty($result)){
            return self::success('禁止失败');
        }else{
            return self::success('禁止成功');
        }
    }
}
