<?php
namespace App\Models;

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
}
