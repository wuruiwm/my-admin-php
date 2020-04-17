<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;

class IndexController extends BaseController
{
    //后台布局
    public function layout()
    {
        return View::make('admin.layout');
    }

    public function index()
    {
        return View::make('admin.index.index');
    }
}
