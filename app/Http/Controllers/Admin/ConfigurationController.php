<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-25 10:03:43
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 15:33:13
 */

namespace App\Http\Controllers\Admin;

use App\Models\ConfigGroup;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends BaseController
{
    /**
     * 配置主页
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $groups = ConfigGroup::with('configurations')->orderBy('sort','asc')->get();
        return View::make('admin.configuration.index',compact('groups'));
    }

    /**
     * 添加配置
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $groups = ConfigGroup::orderBy('sort','asc')->get();
        return View::make('admin.configuration.create',compact('groups'));
    }

    /**
     * 添加配置
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all(['group_id','label','key','val','type','tips','sort']);
        try{
            Configuration::create($data);

        }catch (\Exception $exception){
            return Redirect::back()->withErrors('添加失败');
        }
        Cache::forget('adminConfig');
        return Redirect::to(URL::route('admin.configuration'))->with(['success'=>'添加成功']);
    }

    /**
     * 更新配置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token','id']);
        DB::beginTransaction();
        try{
            foreach ($data as $k => $v){
                DB::table('configuration')->where('key',$k)->update(['val'=>$v]);
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'更新失败']);
        }
        Cache::forget('adminConfig');
        return Response::json(['code'=>0,'msg'=>'更新成功']);
    }
    public function upload(Request $request)
    {
        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif"];
        //返回信息json
        $data = ['code'=>1, 'msg'=>'上传失败', 'data'=>''];
        $file = $request->file('file');

        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的图片";
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getSize() > $maxSize*1024*1024){
                $data['msg'] = "图片大小限制".$maxSize."M";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();
        $disk = Storage::disk('uploads');
        $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
        if($res){
            $data = [
                'code'  => 0,
                'msg'   => '上传成功',
                'data'  => $newFile,
                'url'   => '/uploads/local/'.$newFile,
            ];
        }else{
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }
}
