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
class Admin extends Model
{
	public function admincate()
    {
        //关联角色表
        return $this->belongsTo('AdminCate');
    }

    public function article()
    {
        //关联文章表
        return $this->hasOne('Article');
    }

    public function log()
    {
        //关联日志表
        return $this->hasOne('AdminLog');
    }

    public function attachment()
    {
        //关联附件表
        return $this->hasOne('Attachment');
    }
}
