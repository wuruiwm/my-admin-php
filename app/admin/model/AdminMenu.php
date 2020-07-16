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
class AdminMenu extends Model
{
	public function menulist($menu,$id=0,$level=0){
		
		static $menus = array();
		foreach ($menu as $value) {
			if ($value['pid']==$id) {
				$value['level'] = $level+1;
				if($level == 0)
				{
					$value['str'] = str_repeat('',$value['level']);
				}
				elseif($level == 2)
				{
					$value['str'] = '&emsp;&emsp;&emsp;&emsp;'.'└ ';
				}
				elseif($level == 3)
				{
					$value['str'] = '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'.'└ ';
				}
				else
				{
					$value['str'] = '&emsp;&emsp;'.'└ ';
				}
				$menus[] = $value;
				$this->menulist($menu,$value['id'],$value['level']);
			}
		}
		return $menus;
	}

	public function log()
    {
        //关联操作日志表
        return $this->hasOne('AdminLog');
    }
}
