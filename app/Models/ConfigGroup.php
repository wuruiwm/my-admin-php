<?php
namespace App\Models;

class ConfigGroup extends Base
{
    protected $table = 'config_group';
    protected $fillable = ['name','sort'];

    //配置项
    public function configurations()
    {
        return $this->hasMany('App\Models\Configuration','group_id','id');
    }
}
