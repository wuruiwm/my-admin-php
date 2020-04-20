<?php
namespace App\Models;

class Password extends Base
{
    protected $table = 'password';
    public static function list($number,$limit,$keyword){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('title','like',$like);
                    $query->orwhere('user','like',$like);
                    $query->orwhere('password','like',$like);
                    $query->orwhere('remark','like',$like);
                }
        });
        $count = $model->count();
        $data = $model->offset($number)
            ->limit($limit)
            ->get();
        return ['data'=>$data,'count'=>$count];
    }
}
