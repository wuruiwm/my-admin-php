<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class MicrosoftSubmitRequest extends BaseRequest
{
    public function rules(){
        //获取参数 进行自动验证
        $domain = json_decode(admin_config('office_domain'),true);
        $domain_in = '';
        foreach ($domain as $k => $v){
            $domain_in .= $v.',';
        }
        $sku_id = json_decode(admin_config('office_sku_id'),true);
        $sku_id_in = '';
        foreach ($sku_id as $k =>$v){
            $sku_id_in .= $v['sku_id'].',';
        }
        $rules = [
            'display_name' => 'required|max:255',
            'user_name' => 'required|max:255',
            'domain' => 'required|in:'.rtrim($domain_in,','),
            'sku_id' => 'required|in:'.rtrim($sku_id_in,','),
        ];
        return $rules;
    }
    public function messages(){
        return [
            'display_name.required'=>'请输入姓名',
            'display_name.max'=>'姓名最大为255字符',
            'user_name.required'=>'请输入用户名',
            'user_name.max'=>'用户名最大为255字符',
            'domain.required'=>'请选择注册域名',
            'domain.in'=>'哟，小伙子还会post请求啊',
            'sku_id.required'=>'请选择订阅',
            'sku_id.in'=>'哟，小伙子还会post请求啊',
        ];
    }
}
