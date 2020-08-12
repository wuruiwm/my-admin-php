<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Requests\PageRequest;
use App\Http\Requests\RequiredIdRequest;
use App\Models\Docking;
use Illuminate\Http\Request;

class DockingController extends BaseController
{
    public function index(){
        return View::make('admin.docking.index');
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $focking = Docking::list($offset,$limit,$keyword);
        foreach ($focking['data'] as $k =>$v){
            $focking['data'][$k]->add_time = date('Y-m-d',strtotime($v->add_time));
            $focking['data'][$k]->push_time = date('Y-m-d',strtotime($v->push_time));
        }
        return self::success('请求成功',$focking['data'],$focking['count']);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        try {
            Docking::where('id',$id)->delete($id);
            return self::success("删除成功");
        } catch (\Throwable $th) {
            return self::error("删除失败");
        }
    }
    public function create(Request $request){
        $data = $request->input();
        try {
            Docking::create($data);
            return self::success("添加成功");
        } catch (\Throwable $th){
            return self::error("添加失败");
        }
    }
    public function edit(Request $request){
        $data = $request->input();
        try {
            Docking::where('id',$data['id'])->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("修改失败");
        }
    }
}
