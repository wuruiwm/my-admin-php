<?php
namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Microsoft extends Base
{
    protected $table = 'invitation_code';
    private static $token;
    //初始化microsoft Token
    public static function getMicrosoftToken(){
        $tenant_id = admin_config('office365_tenant_id');
        $client_id = admin_config('office365_client_id');
        $client_secret = admin_config('office365_client_secret');
        $url = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/v2.0/token';
        $scope = 'https://graph.microsoft.com/.default';
        $data = [
            'grant_type'=>'client_credentials',
            'client_id'=>$client_id,
            'client_secret'=>$client_secret,
            'scope'=>$scope
        ];
        $result = curl_post($url,$data);
        $result = json_decode($result,true);
        if(!empty($result) && !empty($result['access_token'])){
            self::$token = $result['access_token'];
            return true;
        }
        return false;
    }
    //创建microsoft用户
    public static function createUser($display_name,$user_name,$domain,$sku_id){
        //拼接用户名和域名 生成密码 准备发起创建用户申请
        $user_email = $user_name . '@' . $domain;
        $password = get_rand_string(10);
        $url = 'https://graph.microsoft.com/v1.0/users';
        $data = [
            "accountEnabled"=>true,
            "displayName"=>$display_name,
            "mailNickname"=>$user_name,
            "passwordPolicies"=>"DisablePasswordExpiration, DisableStrongPassword",
            "passwordProfile"=>[
                "password"=>$password,
                "forceChangePasswordNextSignIn"=>true
            ],
            "userPrincipalName"=>$user_email,
            "usageLocation"=>"CN"
        ];
        //创建用户
        $result = self::curlPostJson($url,$data);
        $result = json_decode($result,true);
        if(!empty($result) && !empty($result['error'])){
            if($result['error']['message'] == 'Another object with the same value for property userPrincipalName already exists.'){
                return self::error('前缀被占用,请修改后重试');
            }
            return self::error($result['error']['message']);
        }
        //添加订阅
        $result = self::addsubscribe($user_email,$sku_id);
        if($result->original['code'] == 1){
            return $result;
        }
        $response = [
            'email'=>$user_email,
            'password'=>$password
        ];
        return self::success("申请账号成功",$response);
    }
    //给用户添加订阅
    private static function addsubscribe($user_email,$sku_id){
        $url = 'https://graph.microsoft.com/v1.0/users/' . $user_email . '/assignLicense';
        $data = [
            'addLicenses'=>[
                [
                    'disabledPlans'=>[],
                    'skuId'=>$sku_id
                ],
            ],
            'removeLicenses'=> [],
        ];
        $result = self::curlPostJson($url,$data);
        $result = json_decode($result,true);
        if(!empty($result) && !empty($result['error'])){
            return self::error($result['error']['message']);
        }
        return self::success();
    }
    //修改邀请码状态为已使用
    public static function updateCodeStatus($code,$email){
        $data = [
            'status'=>1,
            'email'=>$email,
        ];
        try{
            self::where('code',$code)->update($data);
            return self::success();
        } catch (\Throwable $th){
            return self::error('数据异常,请联系管理员');
        }
    }
    //microsoft curl post json 带token
    public static function curlPostJson($url,$data){
        $json_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.self::$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    //后台列表
    public static function list($offset,$limit,$keyword,$is_https){
        $model = self::orderBy('id','asc')
            ->where(function($query) use($keyword){
                if(!empty($keyword)){
                    $like = '%'.$keyword.'%';
                    $query->orwhere('code','like',$like);
                    $query->orwhere('email','like',$like);
                }
            })
            ->where(function($query) use($is_https){
                if(is_numeric($is_https)){
                    !empty($is_https) ? $query->where('status',1) : $query->where('status',0);
                }
            });
        $count = $model->count();
        $data = $model->offset($offset)
            ->limit($limit)
            ->get();
        //如果该次列表 账户都为空 则不获取token，避免不必要的请求
        $isGetMicrosoftToken = false;
        //获取账户状态
        foreach ($data as $k =>$v){
            if(!empty($v->email)){
                if(empty($isGetMicrosoftToken)){
                    Microsoft::getMicrosoftToken();
                    $isGetMicrosoftToken = true;
                }
                $result = Microsoft::statusAccount($v->email);
                if(!empty($result)){
                    $data[$k]->account_status = 0;
                }else{
                    $data[$k]->account_status = -1;
                }
            }
        }
        return ['data'=>$data,'count'=>$count];
    }
    //后台生成邀请码
    public static function createAll($num){
        $total = $num;
        $success = 0;
        $error = 0;
        for ($i = 0;$i < $num;$i++){
            $code = get_rand_string(admin_config('office_code_rand_num'));
            $data = [
                'code'=>$code,
                'status'=>0,
                'email'=>''
            ];
            try {
                self::create($data);
                $success++;
            } catch (\Throwable $th){
                $error++;
            }
        }
        return [
            'total'=>$total,
            'success'=>$success,
            'error'=>$error,
        ];
    }
    //删除账户
    public static function deleteAccount($email){
        $url = "https://graph.microsoft.com/beta/users/".$email;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.self::$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($http_code == 204){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    //获取账户状态
    public static function statusAccount($email){
        //先查缓存
        $cache = self::cacheStatusAccount($email,1);
        if(!empty($cache)){
            //存入缓存的账户状态  1为正常  2为被禁止登录
            if($cache == 1){
                return true;
            }else if($cache == 2){
                return false;
            }
        }
        $url = "https://graph.microsoft.com/beta/users/".$email;
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.self::$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_TIMEOUT, 5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data);
        if(!empty($data)){
            if(!empty($data->accountEnabled)){
                self::cacheStatusAccount($email,0,1);
            }else{
                self::cacheStatusAccount($email,0,2);
            }
            return $data->accountEnabled;
        }else{
            return false;
        }
    }
    //允许账户登录
    public static function activeAccount($email){
        self::cacheStatusAccount($email,2);
        $url = "https://graph.microsoft.com/beta/users/".$email;
        $json_string = '{"accountEnabled":"true"}';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.self::$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    //禁止账户登录
    public static function inactiveAccount($email){
        self::cacheStatusAccount($email,2);
        $url = "https://graph.microsoft.com/beta/users/".$email;
        $json_string = '{"accountEnabled":"false"}';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.self::$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    //缓存账户状态 0存入 1获取状态 2删除
    public static function cacheStatusAccount($email,$status,$value = 0){
        $key = 'office365_status_accoun_'.$email;
        if($status == 0){
            return Cache::put($key,$value);
        }else if($status == 1){
            return Cache::get($key,$value);
        }else if($status == 2){
            return Cache::forget($key);
        }
        return true;
    }
}
