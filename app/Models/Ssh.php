<?php
namespace App\Models;

class Ssh extends Base
{
    protected $table = 'ssh';
    public static function list($offset,$limit,$keyword){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('title','like',$like);
                    $query->orwhere('host','like',$like);
                    $query->orwhere('port','like',$like);
                    $query->orwhere('user','like',$like);
                    $query->orwhere('password','like',$like);
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
        $data["remark"] = $request->input("remark");
        return $data;
    }
}
