<?php
namespace App\Models;

class ShortLinks extends Base
{
    protected $table = 'short_links';
    public static function list($offset,$limit,$keyword){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('tail','like',$like);
                    $query->orwhere('link','like',$like);
                    $query->orwhere('remark','like',$like);
                }
            });
        $count = $model->count();
        $data = $model->offset($offset)
            ->limit($limit)
            ->get();
        return ['data'=>$data,'count'=>$count];
    }
    public static function getData($request){
        $data = $request->validated();
        $data["remark"] = !empty($request->input("remark")) ? $request->input("remark") : '';
        return $data;
    }
}
