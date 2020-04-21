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
        $data["remark"] = !empty($request->input("remark")) ? $request->input("remark") : '';
        if(!empty(admin_config('is_ssh_parse'))){
            $data['host'] = self::parse($data['host']);
        }
        return $data;
    }
    public static function parse($string){
        $string = trim($string);
        if(filter_var($string,FILTER_VALIDATE_IP)){
            $host = $string;
        }else if(!empty(parse_url($string)['host'])){
            $host = parse_url($string)['host'];
        }else{
            $host = $string;
        }
        return $host;
    }
}
