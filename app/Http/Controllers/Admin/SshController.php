<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Requests\PageRequest;
use App\Http\Requests\RequiredIdRequest;
use App\Http\Requests\SshCreateRequest;
use App\Http\Requests\SshEditRequest;
use App\Models\Ssh;

class SshController extends BaseController
{
    public function index(){
        return View::make('admin.ssh.index');
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $ssh = Ssh::list($offset,$limit,$keyword);
        return self::success('请求成功',$ssh['data'],$ssh['count']);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        try {
            Ssh::where('id',$id)->delete($id);
            return self::success("删除成功");
        } catch (\Throwable $th) {
            return self::error("删除失败");
        }
    }
    public function create(SshCreateRequest $request){
        $data = Ssh::getData($request);
        try {
            Ssh::create($data);
            return self::success("添加成功");
        } catch (\Throwable $th){
            return self::error("添加失败");
        }
    }
    public function edit(SshEditRequest $request){
        $data = Ssh::getData($request);
        try {
            Ssh::where('id',$data['id'])->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("修改失败");
        }
    }
}
