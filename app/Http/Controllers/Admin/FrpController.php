<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Requests\PageRequest;
use App\Http\Requests\RequiredIdRequest;
use App\Http\Requests\FrpCreateRequest;
use App\Http\Requests\FrpUpdateRequest;
use App\Models\Frp;

class FrpController extends BaseController
{
    public function index(){
        return View::make('admin.frp.index');
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $is_https = $request->input("is_https");
        $frp = Frp::list($offset,$limit,$keyword,$is_https);
        return self::success('请求成功',$frp['data'],$frp['count']);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        try {
            Frp::where('id',$id)->delete($id);
            return self::success("删除成功");
        } catch (\Throwable $th) {
            return self::error("删除失败");
        }
    }
    public function create(FrpCreateRequest $request){
        $data = Frp::getData($request);
        if(!Frp::checkDomainName($data['domain_name'])){
            return self::error("请输入正确的域名");
        }
        try {
            Frp::create($data);
            return self::success("添加成功");
        } catch (\Throwable $th){
            return self::error("添加失败");
        }
    }
    public function edit(FrpUpdateRequest $request){
        $data = Frp::getData($request);
        if(!Frp::checkDomainName($data['domain_name'])){
            return self::error("请输入正确的域名");
        }
        try {
            Frp::where('id',$data['id'])->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("修改失败");
        }
    }
    public function httpsSwitch(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        $is_https = $request->input('is_https');
        !empty($is_https) ? $data['is_https'] = 1 : $data['is_https'] = 0;
        try {
            Frp::where('id',$id)->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("修改失败");
        }
    }
}
