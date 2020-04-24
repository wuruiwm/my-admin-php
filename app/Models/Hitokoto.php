<?php
namespace App\Models;

class Hitokoto extends Base
{
    protected $table = 'hitokoto';
    public static function typeTransformation($string){
        $type = 1;
        switch ($string) {
            case "a"://动画
                $type = 1;
                break;
            case "b"://漫画
                $type = 2;
                break;
            case "c"://游戏
                $type = 3;
                break;
            case "d"://文学
                $type = 4;
                break;
            case "e"://原创
                $type = 5;
                break;
            case "f"://来自网络
                $type = 6;
                break;
            case "g"://其他
                $type = 7;
                break;
            case "h"://影视
                $type = 8;
                break;
            case "i"://诗词
                $type = 9;
                break;
            case "j"://网易云
                $type = 10;
                break;
            case "k"://哲学
                $type = 11;
                break;
            case "l"://抖机灵
                $type = 12;
                break;
        }
        return $type;
    }
}
