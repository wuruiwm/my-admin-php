<?php
namespace App\Models;

class Category extends Base
{

    protected $guarded = ['id'];

    //子分类
    public function childs()
    {
        return $this->hasMany('App\Models\Category','parent_id','id');
    }

    //所有子类
    public function allChilds()
    {
        return $this->childs()->with('allChilds');
    }

    //分类下所有的文章
    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

}
