<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Models\Password;
use App\Http\Requests\PageRequest;
use App\Http\Requests\RequiredIdRequest;
use App\Http\Requests\PasswordCreateRequest;
use App\Http\Requests\PasswordEditRequest;

class PasswordController extends BaseController
{
    public function index(){
        return View::make('admin.password.index');
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $password = Password::list($offset,$limit,$keyword);
        return self::success('请求成功',$password['data'],$password['count']);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        try {
            Password::where('id',$id)->delete($id);
            return self::success("删除成功");
        } catch (\Throwable $th) {
            return self::error("删除失败");
        }
    }
    public function create(PasswordCreateRequest $request){
        $data = Password::getData($request);
        try {
            Password::create($data);
            return self::success("添加成功");
        } catch (\Throwable $th){
            return self::error("添加失败");
        }
    }
    public function edit(PasswordEditRequest $request){
        $data = Password::getData($request);
        try {
            Password::where('id',$data['id'])->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("修改失败");
        }
    }
}
