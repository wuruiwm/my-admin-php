<?php
namespace App\Models;

class Password extends Base
{
    protected $table = 'password';
    public static function list($offset,$limit,$keyword){
        if(!empty(admin_config("is_password_encrypt"))){
            $model = self::orderBy('id','asc');
            $data = $model->get();
            foreach ($data as $k => $v){
                $v->title = password_decrypt($v->title);
                $v->user = password_decrypt($v->user);
                $v->password = password_decrypt($v->password);
                $v->remark = password_decrypt($v->remark);
                if(!empty($keyword)){
                    $is_set = false;
                    if(strpos($v->title,$keyword) !== false || strpos($v->user,$keyword) !== false || strpos($v->password,$keyword) !== false || strpos($v->remark,$keyword) !== false){
                        $is_set = true;
                    }
                    if(!$is_set){
                        unset($data[$k]);
                    }
                }
            }
            $count = count($data);
            $after = $offset + $limit;
            $i = 0;
            $tmp_data = [];
            foreach ($data as $k => $v){
                if($i >= $after){
                    break;
                }
                if($i >= $offset){
                    $tmp_data[] = $v;
                }
                $i++;
            }
            $data = $tmp_data;
        }else{
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
            $data = $model->offset($offset)
                ->limit($limit)
                ->get();
        }
        return ['data'=>$data,'count'=>$count];
    }
    public static function getData($request){
        $data = $request->validated();
        $data["remark"] = !empty($request->input("remark")) ? $request->input("remark") : '';
        !empty($data["user"]) || $data['user'] = '';
        if(!empty(admin_config("is_password_encrypt"))){
            $data['title'] = password_encrypt($data['title']);
            $data['user'] = password_encrypt($data['user']);
            $data['password'] = password_encrypt($data['password']);
            $data['remark'] = password_encrypt($data['remark']);
        }
        return $data;
    }
}
