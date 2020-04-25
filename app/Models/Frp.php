<?php
namespace App\Models;

class Frp extends Base
{
    protected $table = 'frp';
    public static function list($offset,$limit,$keyword,$is_https){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('domain_name','like',$like);
                    $query->orwhere('remark','like',$like);
                }
            })
            ->where(function($query) use($is_https){
                if(is_numeric($is_https)){
                    !empty($is_https) ? $query->where('is_https',1) : $query->where('is_https',0);
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
    public static function checkDomainName($domain_name){
        try {
            if(!empty(dns_get_record($domain_name))){
                return true;
            }
            return false;
        } catch (\Throwable $th){
            return false;
        }
    }
}
