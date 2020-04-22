<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests\PageRequest;
use App\Http\Requests\RequiredIdRequest;
use App\Http\Requests\ShortLinksCreateRequest;
use App\Http\Requests\ShortLinksEditRequest;
use App\Models\ShortLinks;

class ShortLinksController extends BaseController
{
    public function index(){
        !empty($short_links_domain_name = admin_config('short_links_domain_name')) || $short_links_domain_name = domain_name();
        return View::make('admin.short_links.index',['short_links_domain_name'=>$short_links_domain_name]);
    }
    public function list(PageRequest $request){
        $data = $request->validated();
        $limit = $data['limit'];
        $offset = ($data['page'] - 1) * $limit;
        $keyword = $request->input("keyword");
        $short_links = ShortLinks::list($offset,$limit,$keyword);
        return self::success('请求成功',$short_links['data'],$short_links['count']);
    }
    public function delete(RequiredIdRequest $request){
        $id = $request->validated()['id'];
        try {
            ShortLinks::where('id',$id)->delete($id);
            return self::success("删除成功");
        } catch (\Throwable $th) {
            return self::error("删除失败");
        }
    }
    public function create(ShortLinksCreateRequest $request){
        $data = ShortLinks::getData($request);
        try {
            ShortLinks::create($data);
            return self::success("添加成功");
        } catch (\Throwable $th){
            return self::error("添加失败,可能后缀已被占用,请修改后重试");
        }
    }
    public function edit(ShortLinksEditRequest $request){
        $data = ShortLinks::getData($request);
        try {
            ShortLinks::where('id',$data['id'])->update($data);
            return self::success("修改成功");
        } catch (\Throwable $th) {
            return self::error("添加失败,可能后缀已被占用,请修改后重试");
        }
    }
    public function randTail(Request $request){
        !empty($num = admin_config('short_links_rand_number')) || $num = 4;
        $tail = get_rand_string($num);
        while(true){
            if(empty(ShortLinks::where('tail',$tail)->first())){
                break;
            }
            $tail = get_rand_string($num);
        }
        return self::success("随机生成后缀成功",null,null,['tail'=>$tail]);
    }
}
