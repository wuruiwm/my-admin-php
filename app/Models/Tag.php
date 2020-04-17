<?php
namespace App\Models;

class Tag extends Base
{

    protected $guarded = ['id'];

    //与资讯多对多关联
    public function articles()
    {
        return $this->belongsToMany('App\Models\Article','article_tag','tag_id','article_id');
    }

}
