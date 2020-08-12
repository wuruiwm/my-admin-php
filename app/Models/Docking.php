<?php
namespace App\Models;

class Docking extends Base
{
    protected $table = 'docking';
    public static function list($offset,$limit,$keyword){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('service_name','like',$like);
                    $query->orwhere('name','like',$like);
                    $query->orwhere('mobile','like',$like);
                    $query->orwhere('member_type','like',$like);
                    $query->orwhere('province','like',$like);
                    $query->orwhere('city','like',$like);
                    $query->orwhere('service_region','like',$like);
                    $query->orwhere('service_project','like',$like);
                    $query->orwhere('saturated','like',$like);
                    $query->orwhere('sale_name','like',$like);
                    $query->orwhere('record','like',$like);
                    $query->orwhere('ability','like',$like);
                    $query->orwhere('saturated','like',$like);
                }
            });
        $count = $model->count();
        $data = $model->offset($offset)
            ->limit($limit)
            ->get();
        return ['data'=>$data,'count'=>$count];
    }
}
