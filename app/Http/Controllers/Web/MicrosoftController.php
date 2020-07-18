<?php
/*
 * @Author: 傍晚升起的太阳
 * @QQ: 1250201168
 * @Email: wuruiwm@qq.com
 * @Date: 2020-02-27 11:08:53
 * @LastEditors: 傍晚升起的太阳
 * @LastEditTime: 2020-03-02 09:29:49
 */

namespace App\Http\Controllers\Web;

use App\Http\Requests\MicrosoftSubmitRequest;
use App\Models\Microsoft;

class MicrosoftController extends BaseController
{
    //microsoft office365 自助注册 提交接口
    public function submit(MicrosoftSubmitRequest $request){
        $data = $request->validated();
        $invitation_code = $request->input('invitation_code');
        //验证邀请码
        if(!empty(admin_config('office365_is_invitation_code'))){
            if(empty($invitation_code)){
               return self::error("请输入邀请码");
            }
            $microsoft = Microsoft::where('code',$invitation_code)->first();
            if(empty($microsoft)){
                return self::error("邀请码不存在,请确认邀请码后,重试");
            }
            if($microsoft->status == 1){
                return self::error("邀请码已被使用");
            }
        }
        //初始化microsoft Token
        if(!Microsoft::getMicrosoftToken()){
            return self::error("获取token失败,请检查参数配置是否正确");
        }
        //创建用户 并添加订阅
        $result = Microsoft::createUser($data['display_name'],$data['user_name'],$data['domain'],$data['sku_id']);
        if($result->original['code'] == 1){
            return $result;
        }
        //修改邀请码状态
        if(!empty(admin_config('office365_is_invitation_code'))){
            $tmp_result = Microsoft::updateCodeStatus($invitation_code,$result->original['data']['email']);
            if($tmp_result->original['code'] == 1){
                return $tmp_result;
            }
        }
        return $result;
    }
}
