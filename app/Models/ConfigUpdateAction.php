<?php
namespace App\Models;

class ConfigUpdateAction
{
    public static function is_password_encrypt($data){
         $new_is_password_encrypt = $data['is_password_encrypt'];
         $old_is_password_encrypt = admin_config("is_password_encrypt");
         $new_password_key = $data['password_key'];
         $old_password_key = admin_config("password_key");
         //状态被改变 但是密钥不变 则只解密或者加密
         if($old_is_password_encrypt != $new_is_password_encrypt && $old_password_key == $new_password_key){
             self::password_crypt($new_is_password_encrypt,$old_password_key);
         }
         //状态不变 密钥被改变
         if($old_is_password_encrypt == $new_is_password_encrypt && $old_password_key != $new_password_key){
             //加密开启状态 先用旧密钥解密 再用新密钥加密
             if($old_is_password_encrypt == 1){
                 self::password_crypt(0,$old_password_key);
                 self::password_crypt(1,$new_password_key);
             }
         }
         //状态被改变 密钥也被改变
         if($old_is_password_encrypt != $new_is_password_encrypt && $old_password_key != $new_password_key){
             if($new_is_password_encrypt == 1){
                 //新状态为开启加密，则原来未加密，只需要用新密钥去加密数据即可
                 self::password_crypt(1,$new_password_key);
             }else{
                 //新状态为关闭加密，则原来已加密，只需要用旧密钥去解密数据即可
                 self::password_crypt(0,$old_password_key);
             }
         }
    }
    /**
     * 利用key加密解密 password表的数据
     * @param $type 0解密，1加密
     * @param $key
    */
    private static function password_crypt($type = 0,$key){
        $list = Password::get();
        $crypt = function($str) use($type,$key){
            if($type == 1){
                return password_encrypt($str,$key);
            }else{
                return password_decrypt($str,$key);
            }
        };
        foreach ($list as $k => $v){
            $v->title = $crypt($v->title);
            $v->user = $crypt($v->user);
            $v->password = $crypt($v->password);
            $v->remark = $crypt($v->remark);
            $v->save();
        }
    }
}
