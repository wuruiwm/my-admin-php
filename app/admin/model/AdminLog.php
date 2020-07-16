<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2017 https://nikm.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 傍晚升起的太阳 < wuruiwm@qq.com >
// +----------------------------------------------------------------------


namespace app\admin\model;

use \think\Model;
class AdminLog extends Model
{
	public function admin()
    {
        //关联管理员表
        return $this->belongsTo('Admin');
    }


    public function menu()
    {
        //关联菜单表
        return $this->belongsTo('AdminMenu');
    }
}
